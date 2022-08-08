#include <random>
#include <iostream>
#include <algorithm>
#include <getConfig.hpp>
#include <global.hpp>
#include <Poule.hpp>

void clear(std::vector<std::string>& equipe)
{
    for (std::vector<std::string>::const_iterator it = equipe.begin(); it != equipe.end();)
    {
        if (Equipe::byName(*it) == nullptr)
        {
            std::cout << "Attention, l'equipe " << *it << " n'existe pas !" << std::endl;
            it = equipe.erase(it);
        }
        else
            ++it;
    }
}

void tiragePoule(const std::vector<std::string>& equipe, const std::vector<std::string>& nomPoules, std::map<std::string, Poule>& out)
{
    // nombre de poules
    const int nbPoules = (int)nomPoules.size();
    const int nbEquipes = (int)std::ceil((int)equipe.size() / nbPoules);

    // init random
    static std::random_device rd;   // seed
    static std::default_random_engine gen(rd());
    std::uniform_int_distribution<> rand(0, nbPoules-1);

    // copie de toutes les equipes
    std::vector<std::string> all = equipe;
    std::vector<std::string> allAmi;

    // initialisation des poules
    for (std::vector<std::string>::const_iterator it = nomPoules.begin(); it != nomPoules.end(); it++)
        out[*it].setIdPoule(POULES.find(*it)->second);

    // traitement des equipes avec ami
    for (std::vector<std::string>::const_iterator it = all.begin(); it != all.end();)
    {
        const Equipe* equipe = Equipe::byName(*it);

        // est-ce que l'equipe est valide ?
        if (!equipe || !equipe->isValid())
        {
            it = all.erase(it);
            continue;
        }
        // est-ce que l'equipe a un ami valide ?
        const Equipe* ami = equipe->ami();
        if (!ami || !ami->isValid())
        {
            it++;
            continue;
        }
        // est-ce que l'ami est au meme niveau ?
        std::vector<std::string>::const_iterator jt = std::find(all.begin(), all.end(), ami->nom());
        if (jt != all.end())
        {
            // tirages aleatoires
            int p1 = rand(gen);
            for (int n = 0; n < nbPoules && (int)out[nomPoules[p1]].equipes().size() >= nbEquipes; ++n, p1 = (p1 + 1) % nbPoules);
            int p2 = (p1 + 1) % nbPoules;
            for (int n = 0; n < nbPoules && (int)out[nomPoules[p2]].equipes().size() >= nbEquipes; ++n, p2 = (p2 + 1) % nbPoules);

            // ajouts
            out[nomPoules[p1]].addEquipe(*it);
            out[nomPoules[p2]].addEquipe(*jt);

            // increment
            all.erase(jt);
            it = std::find(all.begin(), all.end(), equipe->nom());
            it = all.erase(it);
        }
        else
        {
            it++;
        }
    }

    // traitement des autres equipes
    for (const std::string& name : all)
    {
        // on a deja teste si l'equipe est valide
        int p1 = rand(gen);
        for (int n = 0; n < nbPoules && (int)out[nomPoules[p1]].equipes().size() >= nbEquipes; ++n, p1 = (p1 + 1) % nbPoules);

        // ajouts
        out[nomPoules[p1]].addEquipe(name);
    }
}

int main(int argc, char **argv)
{
    // fichier .ini
    std::string config = "config.ini";
    if (argc > 1)
        config = argv[1];

    // fichier resultats
    const std::string resultat = getConfigAsString("Fichier resultat", "resultat.ini", config);

    // recuperation des equipes
    std::string filename = getConfigAsString("Fichier CSV equipe", "data/f_equipe.csv", config);
    Equipe::readCSV(filename);

    // recuperation equipes niveau
    std::vector<std::string> niveauA = getConfigAsVectorString("Equipes niveau A", {}, config);
    std::vector<std::string> niveauB = getConfigAsVectorString("Equipes niveau B", {}, config);
    std::vector<std::string> niveauC = getConfigAsVectorString("Equipes niveau C", {}, config);
    std::vector<std::string> niveauD = getConfigAsVectorString("Equipes niveau D", {}, config);

    // nettoyage des niveaux (equipes qui n'existent pas)
    clear(niveauA);
    clear(niveauB);
    clear(niveauC);
    clear(niveauD);

    // tirage poules
    std::map<std::string, Poule> poule;
    const std::vector<std::string> pouleNameA = getConfigAsVectorString("Noms poule A", {}, config);
    const std::vector<std::string> pouleNameB = getConfigAsVectorString("Noms poule B", {}, config);
    const std::vector<std::string> pouleNameC = getConfigAsVectorString("Noms poule C", {}, config);
    const std::vector<std::string> pouleNameD = getConfigAsVectorString("Noms poule D", {}, config);
    tiragePoule(niveauA, pouleNameA, poule);
    tiragePoule(niveauB, pouleNameB, poule);
    tiragePoule(niveauC, pouleNameC, poule);
    tiragePoule(niveauD, pouleNameD, poule);

    // arbitrage
    for (std::pair<const std::string, Poule>& it : poule)
    {
        const std::string& name = it.first;
        Poule& p = it.second;
        const std::string arbitre = getConfigAsString("Arbitre poule " + name, "", config);
        Poule& a = poule[arbitre];
        for (Equipe* const& jt : a.equipes())
            p.addArbitre(jt->nom());
    }

    // generation matchs
    for (std::map<std::string, Poule>::iterator it = poule.begin(); it != poule.end(); it++)
        it->second.genereMatchs();

    // sauvegarde de la liste des matchs au format CSV
    filename = getConfigAsString("Fichier CSV match", "data/f_match.csv", config);
    Match::toCSV(filename);

    std::cout << "Nombre total de matchs: " << (int)Match::getMatch().size() << std::endl;
    setConfigInt("Nombre total de matchs", (int)Match::getMatch().size(), resultat);

    return 0;
}