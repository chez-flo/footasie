#include <iostream>
#include <map>
#include <getConfig.hpp>
#include <Equipe.hpp>
#include <Creneau.hpp>
#include <Match.hpp>

int main(int argc, char** argv)
{
    // chargement initial de la liste des equipes
    std::string filename = getConfigAsString("Fichier CSV equipe", "data/f_equipe.csv", "config.ini");
    Equipe::readCSV(filename);

    // chargement de la liste des creneaux et des matchs au format CSV
    filename = getConfigAsString("Fichier CSV creneau", "data/f_creneau.csv", "config.ini");
    Creneau::fromCSV(filename);
    filename = getConfigAsString("Fichier CSV match", "data/f_match.csv", "config.ini");
    Match::fromCSV(filename);

    return 0;
}