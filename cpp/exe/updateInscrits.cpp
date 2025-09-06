#include <iostream>
#include <fstream>
#include <map>
#include <global.hpp>
#include <getConfig.hpp>
#include <Inscrit.hpp>
#include <Joueur.hpp>

int main(int argc, char** argv)
{
    // fichier .ini
    std::string config = "config.ini";
    if (argc > 1)
        config = argv[1];

    // fichier d'entree
    const std::string inscrits = getConfigAsString("Fichier inscrits", "data/wp_db7_forms.csv", config);
    Inscrit::readCSV(inscrits);
    const std::string joueurs = getConfigAsString("Fichier joueurs", "data/f_joueur.csv", config);
    Joueur::readCSV(joueurs);

    // fichier de requetes
    const std::string requetes = getConfigAsString("Fichier requetes", "data/requetes_inscrit.txt", config);
    Inscrit::toSql(requetes);

    return 0;
}