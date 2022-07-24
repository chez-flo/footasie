#include <iostream>
#include <map>
#include <global.hpp>
#include <getConfig.hpp>
#include <Equipe.hpp>
#include <Creneau.hpp>
#include <Match.hpp>

// return true si date du match OK
bool testPont(const Creneau& creneau, const std::vector<Date>& pont)
{
    for (const Date& d : pont)
    {
        if (d == creneau.date())
            return false;
    }
    return true;
}

// return true si preferences OK
bool testPreference(const Match& match, const Creneau& creneau)
{
    // tests equipe 1
    const Equipe* eq1 = match.equipe1();
    // test terrain
    if (creneau.stade() == eq1->terrain())  return false;
    // test entrainement
    if (creneau.date().isThisDay(eq1->entrainement()))  return false;

    // tests equipe 2
    const Equipe* eq2 = match.equipe2();
    // test terrain
    if (creneau.stade() == eq2->terrain())  return false;
    // test entrainement
    if (creneau.date().isThisDay(eq2->entrainement()))  return false;

    return true;
}

// return true si enchainement OK
bool testEnchainement(const Match& match, const Creneau& creneau, unsigned int enchainement, std::map<unsigned int, Date>& lastMatch)
{
    // tests equipe 1
    const Equipe* eq1 = match.equipe1();
    if (creneau.date() < lastMatch[eq1->id() + 5])  return false;
    // tests equipe 2
    const Equipe* eq2 = match.equipe1();
    if (creneau.date() < lastMatch[eq2->id() + 5])  return false;

    return true;
}

// return true si arbitrage OK
bool testArbitrage(const Match& match, const Creneau& creneau)
{
    // creneaux du jour
    std::vector<Creneau> creneaux = Creneau::getCreneauxFromDate(creneau.date());

    // analyse des creneaux du jour
    for (const Creneau& c : creneaux)
    {
        // recuperation du match associe
        const Match* m = Match::byId(c.match());
        if (m == nullptr)   continue;

        // est-ce que l'une des equipe joue ou arbitre ?
        if (match.isThisEquipeParticipating(m->equipe1()))  return false;
        if (match.isThisEquipeParticipating(m->equipe2()))  return false;
        if (match.isThisEquipeParticipating(m->arbitre1())) return false;
        if (match.isThisEquipeParticipating(m->arbitre2())) return false;
    }

    return true;
}

void createChampionnat(const unsigned int enchainement, const std::vector<Date>& pont)
{
    // map des derniers matchs joues par l'equipe (pour conserver l'ordre des journees)
    std::map<unsigned int, Date> lastMatch;

    // boucle sur les journees
    unsigned int j = 1u;
    for (std::vector<Match> journee = Match::getJournee(j); !journee.empty(); journee = Match::getJournee(++j))
    {
        // boucle sur les matchs de la journee
        for (const Match& match : journee)
        {
            bool OK = false;
            // recherche du premier bon creneau
            for (Creneau& creneau : Creneau::getCreneaux())
            {
                // tests
                if (creneau.match() != SAISON * 10000u)                         continue;   // creneau libre
                if (!testPont(creneau, pont))                                   continue;   // pont
                if (!testPreference(match, creneau))                            continue;   // preferences (jour et terrain)
                if (!testEnchainement(match, creneau, enchainement, lastMatch)) continue;   // enchainement
                if (!testArbitrage(match, creneau))                             continue;   // arbitrage

                // tous les tests sont OK, on met a jour le creneau et on arrete la boucle
                OK = true;
                creneau.setMatch(match);
                break;
            }

            if (!OK)    std::cout << "ATTENTION, impossible de programmer la rencontre " << (int)match.id() << std::endl;
        }
    }
}

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

    // parametre : enchainement des matchs
    const unsigned int enchainement = getConfigAsUInt("Enchainement des matchs", 5u, "config.ini");

    // liste des ponts
    std::vector<Date> pont = {
        Date("04/01/2023"),     // juste apres nouvel an
        Date("05/01/2023"),     // juste apres nouvel an
        Date("06/01/2023"),     // juste apres nouvel an
        Date("19/05/2023")      // pont de la Pentecote
    };
    pont = getConfigAsVectorDate("Ponts", pont, "config.ini");

    // creation du championnat
    createChampionnat(enchainement, pont);

    // sauvegarde championnat (format CSV)
    filename = getConfigAsString("Fichier CSV championnat", "data/f_creneau_championnat.csv", "config.ini");
    Creneau::toCSV(filename);

    return 0;
}