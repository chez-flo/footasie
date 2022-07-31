#include <iostream>
#include <map>
#include <getConfig.hpp>
#include <Creneau.hpp>

bool isFerie(const Date& date, const std::vector<Date>& ferie)
{
    for (const Date& d : ferie)
    {
        if (d == date)
            return true;
    }

    return false;
}

bool isVacances(const Date& date, const std::map<std::string, std::vector<Date> >& vacances)
{
    for (const std::pair<std::string, std::vector<Date> >& v : vacances)
    {
        if (date >= v.second[0] && date < v.second[1])
            return true;
    }
    return false;
}

int main(int argc, char **argv)
{
    // annee
    std::vector<Date> annee = {
        Date("01/09/2022"),     // rentree
        Date("08/07/2023")      // vacances
    };
    annee = getConfigAsVectorDate("Annee", annee, "config.ini");

    // jours feries
    std::vector<Date> ferie = { // valable pour tous les stades
        Date("11/11/2022"),     // 11 novembre
        Date("10/04/2023"),     // paques
        Date("01/05/2023"),     // 1er mai
        Date("08/05/2023"),     // 8 mai
        Date("18/05/2023"),     // ascension
        Date("29/05/2023")      // pentecote
    };
    ferie = getConfigAsVectorDate("Jours feries", ferie, "config.ini");

    // vacances
    std::vector<Date> toussaint = {
        Date("22/10/2022"),
        Date("07/11/2022")
    };
    toussaint = getConfigAsVectorDate("Vacances Toussaint", toussaint, "config.ini");
    std::vector<Date> noel = {
        Date("17/12/2022"),
        Date("03/01/2023")
    };
    noel = getConfigAsVectorDate("Vacances de Noel", noel, "config.ini");
    std::vector<Date> hiver = {
        Date("11/02/2023"),
        Date("27/02/2023")
    };
    hiver = getConfigAsVectorDate("Vacances d'hiver", hiver, "config.ini");
    std::vector<Date> printemps = {
        Date("15/04/2023"),
        Date("02/05/2023")
    };
    printemps = getConfigAsVectorDate("Vacances de printemps", printemps, "config.ini");
    std::map<std::string, std::vector<Date> > vacances = {
        {"toussaint", toussaint},
        {"noel", noel},
        {"hiver", hiver},
        {"printemps", printemps}
    };

    // terrains
    std::vector<int> dispoChabert = { 0, 2, 2, 2, 2, 1, 0 };
    dispoChabert = getConfigAsVectorInt("Disponibilite Chabert", dispoChabert, "config.ini");

    std::vector<int> dispoBiot = { 0, 2, 1, 2, 2, 2, 0 };
    dispoBiot = getConfigAsVectorInt("Disponibilite Biot", dispoBiot, "config.ini");

    std::vector<int> dispoFontonne = { 0, 2, 2, 0, 2, 2, 0 };
    dispoFontonne = getConfigAsVectorInt("Disponibilite Fontonne", dispoFontonne, "config.ini");

    // construction de la liste des creneaux disponibles
    for (Date date = annee[0]; date < annee[1]; date += 1)
    {
        // test ferie ou vacances
        if (!isFerie(date, ferie) && !isVacances(date, vacances))
        {
            // chabert
            for (int n = 0; n < dispoChabert[date.weekday()]; ++n)
                Creneau c(date, 1);
            // biot
            for (int n = 0; n < dispoBiot[date.weekday()]; ++n)
                Creneau c(date, 2);
            // fontonne
            for (int n = 0; n < dispoFontonne[date.weekday()]; ++n)
                Creneau c(date, 3);
        }
    }

    // sauvegarde de la liste des creneaux au format CSV
    const std::string filename = getConfigAsString("Fichier CSV creneau", "data/f_creneau.csv", "config.ini");
    Creneau::toCSV(filename);

    std::cout << "Nombre total de creneaux: " << (int)Creneau::getCreneaux().size() << std::endl;
    setConfigInt("Nombre total de creneaux", (int)Creneau::getCreneaux().size(), "resultat.ini");

    return 0;
}