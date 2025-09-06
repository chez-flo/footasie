#include <random>
#include <iostream>
#include <fstream>
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

void requetePouleEtEquipe(const std::map<std::string, Poule>& poule, const std::string& config)
{
    // fichier de requete
    const std::string filename = getConfigAsString("Fichier Sql equipe", "data/equipe.txt", config);

    // ouverture fichier
    std::fstream handle;
    handle.open(filename.c_str(), std::ios_base::out);

    // ecriture equipes pour chaque poule
    handle << "insert into f_equipe_poule_saison values((select eq_id from f_equipe where eq_nom = \"Creneau Libre\"), (select pou_id from f_poule where pou_nom = \"Creneau Libre\"), \"" << (int)SAISON << "\");" << std::endl;
    handle << "insert into f_equipe_poule_saison values((select eq_id from f_equipe where eq_nom = \"Amical\"), (select pou_id from f_poule where pou_nom = \"Amical\"), \"" << (int)SAISON << "\");" << std::endl;
    for (const std::pair<std::string, Poule>& it : poule)
    {
        const Poule& p = it.second;
        for (const Equipe* e : p.equipes())
            handle << "insert into f_equipe_poule_saison values((select eq_id from f_equipe where eq_nom = \""
                    << e->nom() << "\"), \"" << (int)p.getIdPoule() << "\", \"" << (int)SAISON << "\");" << std::endl;
    }

    // ecriture poule pour chaque poule
    handle << "insert into f_saison values(\"" << (int)SAISON << "\", \"1\", \"0\", \"0\", \"0\", \"0\");" << std::endl;
    handle << "insert into f_saison values(\"" << (int)SAISON << "\", \"2\", \"0\", \"0\", \"0\", \"0\");" << std::endl;
    for (const std::pair<std::string, Poule>& it : poule)
    {
        const Poule& p = it.second;
        const std::string& nom = it.first;
        const std::vector<unsigned int> montee = getConfigAsVectorUInt("Montee/Descente poule " + nom, {2,2}, config);
        handle << "insert into f_saison values(\"" << (int)SAISON << "\", \""
            << (int)p.getIdPoule() << "\", \""
            << (int)p.equipes().size() << "\", \""
            << (int)montee.front() << "\", \""
            << (int)montee.back() << "\", \""
            << (int)p.getIdArbitre() << "\");" << std::endl;
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

    // tirage poules
    std::map<std::string, Poule> poule;
    const unsigned int nbNiveaux = getConfigAsUInt("Nombre de niveaux", 4u, config);
    for (unsigned int N = 1u; N <= nbNiveaux; ++N)
    {
        // recuperation equipes niveau
        std::vector<std::string> niveau = getConfigAsVectorString("Equipes niveau " + std::to_string(N), {}, config);
        // nettoyage du niveau (equipes qui n'existent pas)
        clear(niveau);
        // recuperation des poules de ce niveau
        const std::vector<std::string> pouleName = getConfigAsVectorString("Noms poules niveau " + std::to_string(N), {}, config);
        // tirage
        tiragePoule(niveau, pouleName, poule);
    }

    // arbitrage
    for (std::pair<const std::string, Poule>& it : poule)
    {
        const std::string& name = it.first;
        Poule& p = it.second;
        const std::string arbitre = getConfigAsString("Arbitre poule " + name, "", config);
        Poule& a = poule[arbitre];
        p.setIdArbitre(POULES.find(arbitre)->second);
        for (Equipe* const& jt : a.equipes())
            p.addArbitre(jt->nom());
    }

    // requetes pour poules et equipes
    requetePouleEtEquipe(poule, config);

    // recuperation de la liste des matchs deja prevus
    filename = getConfigAsString("Fichier CSV match entree", "data/f_match_entree.csv", config);
    Match::fromCSV(filename);

    // clear de la liste (maj du min id)
    Match::clear();

    // generation matchs
    for (std::map<std::string, Poule>::iterator it = poule.begin(); it != poule.end(); it++)
        it->second.genereMatchs(true);

    // sauvegarde de la liste des matchs au format CSV
    filename = getConfigAsString("Fichier CSV match", "data/f_match.csv", config);
    Match::toCSV(filename);

    // sauvegarde de la liste des matchs au format Sql
    filename = getConfigAsString("Fichier Sql match", "data/match.txt", config);
    Match::toSql(filename);

    std::cout << "Nombre total de matchs: " << (int)Match::getMatch().size() << std::endl;
    setConfigInt("Nombre total de matchs", (int)Match::getMatch().size(), resultat);

    return 0;
}