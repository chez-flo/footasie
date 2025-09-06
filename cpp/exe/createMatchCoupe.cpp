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

std::vector<std::string> melangePoule(std::vector<std::string> nomPoules)
{
    // init random
    static std::random_device rd;   // seed
    static std::default_random_engine gen(rd());
    // init out
    std::vector<std::string> out;
    // melange
    while (!nomPoules.empty())
    {
        // tirage aleatoire
        std::uniform_int_distribution<> rand(0, (int)nomPoules.size() - 1);
        const int p = rand(gen);
        out.push_back(nomPoules[p]);
        nomPoules.erase(nomPoules.begin() + p);
    }

    return out;
}

void tiragePoule(const std::vector<std::vector<std::string> >& equipe, const std::vector<std::string>& nomPoules, std::map<std::string, Poule>& out)
{
    // initialisation des poules
    for (std::vector<std::string>::const_iterator it = nomPoules.begin(); it != nomPoules.end(); it++)
        out[*it].setIdPoule(POULESCOUPE.find(*it)->second);

    // remplissage des poules au fur et a mesure des chapeaux
    std::vector<std::string> dispoule = melangePoule(nomPoules);
    for (const std::vector<std::string>& chapeau : equipe)
    {
        for (const std::string& eq : chapeau)
        {
            // ajout de l'equipe a la poule
            const std::string& poule = dispoule.back();
            out[poule].addEquipe(eq);
            // retrait de la poule
            dispoule.pop_back();
            if (dispoule.empty())   dispoule = melangePoule(nomPoules);
        }
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
        const std::vector<unsigned int> montee = getConfigAsVectorUInt("Qualification poule coupe " + nom, {2,0}, config);
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

    // chapeaux
    std::vector<std::vector<std::string> > chapeaux;
    const unsigned int nbChapeaux = getConfigAsUInt("Nombre de chapeaux", 4u, config);
    for (unsigned int N = 1u; N <= nbChapeaux; ++N)
    {
        // recuperation equipes chapeau
        std::vector<std::string> chapeau = getConfigAsVectorString("Equipes chapeau " + std::to_string(N), {}, config);
        // nettoyage du niveau (equipes qui n'existent pas)
        clear(chapeau);
        // enregistrement chapeau
        chapeaux.push_back(chapeau);
    }

    // tirage poules
    std::map<std::string, Poule> poule;
    // noms des poules 
    const std::vector<std::string> pouleName = getConfigAsVectorString("Noms poules pour coupe", {}, config);
    // tirage
    tiragePoule(chapeaux, pouleName, poule);

    // arbitrage
    for (unsigned int n=0u; n<pouleName.size();++n)
    {
        const std::string& name = pouleName[n];
        Poule& p = poule[name];
        const unsigned int m = (n + 1u) % (pouleName.size());
        std::string arbitre = pouleName[m];
        arbitre = getConfigAsString("Arbitre de coupe poule " + name, arbitre, config);
        Poule& a = poule[arbitre];
        p.setIdArbitre(POULESCOUPE.find(arbitre)->second);
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
        it->second.genereMatchs(false);

    // sauvegarde de la liste des matchs au format CSV
    filename = getConfigAsString("Fichier CSV match coupe", "data/f_match_coupe.csv", config);
    Match::toCSV(filename);

    // sauvegarde de la liste des matchs au format Sql
    filename = getConfigAsString("Fichier Sql match coupe", "data/match_coupe.txt", config);
    Match::toSql(filename);

    std::cout << "Nombre total de matchs de coupe: " << (int)Match::getMatch().size() << std::endl;
    setConfigInt("Nombre total de matchs de coupe", (int)Match::getMatch().size(), resultat);

    return 0;
}