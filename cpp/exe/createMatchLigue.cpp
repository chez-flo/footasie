#include <random>
#include <iostream>
#include <fstream>
#include <algorithm>
#include <getConfig.hpp>
#include <global.hpp>
#include <Ligue.hpp>

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

void requetePouleEtEquipe(const Ligue& poule, const std::string& config)
{
    // fichier de requete
    const std::string filename = getConfigAsString("Fichier Sql equipe", "data/equipe.txt", config);

    // ouverture fichier
    std::fstream handle;
    handle.open(filename.c_str(), std::ios_base::out);

    // ecriture equipes pour chaque poule
    handle << "insert into f_equipe_poule_saison values((select eq_id from f_equipe where eq_nom = \"Creneau Libre\"), (select pou_id from f_poule where pou_nom = \"Creneau Libre\"), \"" << (int)SAISON << "\");" << std::endl;
    handle << "insert into f_equipe_poule_saison values((select eq_id from f_equipe where eq_nom = \"Amical\"), (select pou_id from f_poule where pou_nom = \"Amical\"), \"" << (int)SAISON << "\");" << std::endl;
    for (const Equipe* e : poule.equipes())
        handle << "insert into f_equipe_poule_saison values((select eq_id from f_equipe where eq_nom = \""
        << e->nom() << "\"), \"" << (int)poule.getIdPoule() << "\", \"" << (int)SAISON << "\");" << std::endl;

    // ecriture poule pour chaque poule
    handle << "insert into f_saison values(\"" << (int)SAISON << "\", \"1\", \"0\", \"0\", \"0\", \"0\");" << std::endl;
    handle << "insert into f_saison values(\"" << (int)SAISON << "\", \"2\", \"0\", \"0\", \"0\", \"0\");" << std::endl;
    const std::vector<unsigned int> montee = getConfigAsVectorUInt("Qualification ligue", { 4,0 }, config);
    handle << "insert into f_saison values(\"" << (int)SAISON << "\", \""
        << (int)poule.getIdPoule() << "\", \""
        << (int)poule.equipes().size() << "\", \""
        << (int)montee.front() << "\", \""
        << (int)montee.back() << "\", \""
        << (int)poule.getIdArbitre() << "\");" << std::endl;
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

    // creation de la ligue
    Ligue poule;
    poule.setIdPoule(getConfigAsUInt("Id poule pour ligue", 50u, config));

    // joue-t-on contre une equipe de notre chapeau ?
    const bool joueTonChapeau = getConfigAsUInt("Joue ton chapeau", 0u) > 0u;

    // chapeaux
    std::vector<std::vector<std::string> > chapeaux;
    const unsigned int nbChapeaux = getConfigAsUInt("Nombre de chapeaux", 4u, config);
    for (unsigned int N = 1u; N <= nbChapeaux; ++N)
    {
        // recuperation equipes chapeau
        std::vector<std::string> chapeau = getConfigAsVectorString("Equipes chapeau " + std::to_string(N), {}, config);
        // nettoyage du niveau (equipes qui n'existent pas)
        clear(chapeau);
        // update info equipe
        for (std::string& equipe : chapeau)
        {
            Equipe::byName(equipe)->setChapeau(N);
            poule.addEquipe(equipe);
        }
        // enregistrement chapeau
        chapeaux.push_back(chapeau);
    }

    // requetes pour poules et equipes
    requetePouleEtEquipe(poule, config);

    // recuperation de la liste des matchs deja prevus
    filename = getConfigAsString("Fichier CSV match entree", "data/f_match_entree.csv", config);
    Match::fromCSV(filename);

    // clear de la liste (maj du min id)
    Match::clear();

    // generation matchs
    poule.genereMatchs(chapeaux, joueTonChapeau);

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