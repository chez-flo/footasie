#include <iostream>
#include <map>
#include <getConfig.hpp>
#include <Creneau.hpp>

int main(int argc, char** argv)
{
    // chargement de la liste des creneaux au format CSV
    const std::string filename = getConfigAsString("Fichier CSV creneau", "data/f_creneau.csv", "config.ini");
    Creneau::fromCSV(filename);

    return 0;
}