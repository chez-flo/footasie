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
    for (const std::string& day : eq1->entrainement())
        if (creneau.date().isThisDay(day))  return false;

    // tests equipe 2
    const Equipe* eq2 = match.equipe2();
    // test terrain
    if (creneau.stade() == eq2->terrain())  return false;
    // test entrainement
    for (const std::string& day : eq2->entrainement())
        if (creneau.date().isThisDay(day))  return false;

    return true;
}

// return true si enchainement OK
bool testEnchainement(const Match& match, const Creneau& creneau, unsigned int enchainement, const std::map<unsigned int, Date>& lastMatch)
{
    // tests equipe 1
    const Equipe* eq1 = match.equipe1();
    std::map<unsigned int, Date>::const_iterator last1 = lastMatch.find(eq1->id());
    if (last1 != lastMatch.end() && creneau.date() < (last1->second + enchainement))  return false;
    // tests equipe 2
    const Equipe* eq2 = match.equipe2();
    std::map<unsigned int, Date>::const_iterator last2 = lastMatch.find(eq2->id());
    if (last2 != lastMatch.end() && creneau.date() < (last2->second + enchainement))  return false;

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

        // est-ce que l'une des equipes joue ou arbitre ?
        if (match.isThisEquipeParticipating(m->equipe1()))  return false;
        if (match.isThisEquipeParticipating(m->equipe2()))  return false;
        if (match.isThisEquipeParticipating(m->arbitre1())) return false;
        if (match.isThisEquipeParticipating(m->arbitre2())) return false;
    }

    return true;
}

// return true si ami OK
bool testAmi(const Match& match, const Creneau& creneau)
{
    // creneaux du jour
    std::vector<Creneau> creneaux = Creneau::getCreneauxFromDate(creneau.date());

    // analyse des creneaux du jour
    for (const Creneau& c : creneaux)
    {
        // recuperation du match associe
        const Match* m = Match::byId(c.match());
        if (m == nullptr)   continue;

        // est-ce que l'une des equipes a un ami qui joue ou arbitre ?
        if (match.isThisEquipePlaying(m->equipe1()->ami())) return false;
        if (match.isThisEquipePlaying(m->equipe2()->ami())) return false;
        if (m->isThisEquipePlaying(match.equipe1()->ami())) return false;
        if (m->isThisEquipePlaying(match.equipe2()->ami())) return false;
    }

    return true;
}

void createCoupe(const unsigned int enchainement, const Date& debut, const std::vector<Date>& pont)
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
                if (creneau.date() < debut)                                     continue;   // date de demarrage
                if (creneau.match() != SAISON * 10000u)                         continue;   // creneau libre
                if (!testPont(creneau, pont))                                   continue;   // pont
                if (!testPreference(match, creneau))                            continue;   // preferences (jour et terrain)
                if (!testEnchainement(match, creneau, enchainement, lastMatch)) continue;   // enchainement
                if (!testArbitrage(match, creneau))                             continue;   // arbitrage
                if (!testAmi(match, creneau))                                   continue;   // ami

                // tous les tests sont OK, on met a jour le creneau et on arrete la boucle
                OK = true;
                creneau.setMatch(match);
                lastMatch[match.equipe1()->id()] = creneau.date();
                lastMatch[match.equipe2()->id()] = creneau.date();
                break;
            }

            if (!OK)    std::cout << "ATTENTION, impossible de programmer la rencontre " << (int)match.id() << std::endl;
        }
    }
}

int main(int argc, char** argv)
{
    // fichier .ini
    std::string config = "config.ini";
    if (argc > 1)
        config = argv[1];

    // fichier resultats
    const std::string resultat = getConfigAsString("Fichier resultat", "resultat.ini", config);

    // chargement initial de la liste des equipes
    std::string filename = getConfigAsString("Fichier CSV equipe", "data/f_equipe.csv", config);
    Equipe::readCSV(filename);

    // chargement de la liste des creneaux et des matchs au format CSV
    filename = getConfigAsString("Fichier CSV creneau coupe", "data/f_creneau.csv", config);
    Creneau::fromCSV(filename);
    filename = getConfigAsString("Fichier CSV match coupe", "data/f_match_coupe.csv", config);
    Match::fromCSV(filename);

    // parametre : enchainement des matchs
    const unsigned int enchainement = getConfigAsUInt("Enchainement des matchs", 5u, config);

    // liste des ponts
    std::vector<Date> pont = {
        Date("01/09/2022"),     // debut de saison 2eme semaine de septembre
        Date("02/09/2022"),     // debut de saison 2eme semaine de septembre
        Date("03/09/2022"),     // debut de saison 2eme semaine de septembre
        Date("04/09/2022"),     // debut de saison 2eme semaine de septembre
        Date("05/09/2022"),     // debut de saison 2eme semaine de septembre
        Date("06/09/2022"),     // debut de saison 2eme semaine de septembre
        Date("07/09/2022"),     // debut de saison 2eme semaine de septembre
        Date("08/09/2022"),     // debut de saison 2eme semaine de septembre
        Date("09/09/2022"),     // debut de saison 2eme semaine de septembre
        Date("10/09/2022"),     // debut de saison 2eme semaine de septembre
        Date("11/09/2022"),     // debut de saison 2eme semaine de septembre
        Date("12/09/2022"),     // debut de saison 2eme semaine de septembre
        Date("13/09/2022"),     // debut de saison 2eme semaine de septembre
        Date("14/09/2022"),     // debut de saison 2eme semaine de septembre
        Date("15/09/2022"),     // debut de saison 2eme semaine de septembre
        Date("16/09/2022"),     // debut de saison 2eme semaine de septembre
        Date("04/01/2023"),     // juste apres nouvel an
        Date("05/01/2023"),     // juste apres nouvel an
        Date("06/01/2023"),     // juste apres nouvel an
        Date("19/05/2023")      // pont de la Pentecote
    };
    pont = getConfigAsVectorDate("Ponts", pont, config);
    Date debut = getConfigAsDate("Debut de la coupe", Date("15/05/2023"), config);

    // nettoyage creneaux non-voulus
    Creneau::clean(debut);

    // creation du championnat
    createCoupe(enchainement, debut, pont);

    // sauvegarde championnat (format CSV)
    filename = getConfigAsString("Fichier CSV coupe", "data/f_creneau_coupe.csv", config);
    Creneau::toCSV(filename);

    // sauvegarde championnat (format Sql)
    filename = getConfigAsString("Fichier Sql coupe", "data/coupe.txt", config);
    Creneau::updateSql(filename);


    // logs
    // nombre de creneaux alloues
    int nbAlloue = 0;
    for (const Creneau& c : Creneau::getCreneaux())
        if (c.match() != SAISON * 10000u)
            ++nbAlloue;
    std::cout << "Nombre de creneaux alloues: " << nbAlloue << std::endl;
    setConfigInt("Nombre de creneaux alloues", nbAlloue, resultat);

    // nombre de creneaux libres en fin d'annee
    int nbLibre = 0;
    for (std::vector<Creneau>::const_reverse_iterator it = Creneau::getCreneaux().rbegin(); it != Creneau::getCreneaux().rend(); ++it)
    {
        if (it->match() != SAISON * 10000u)
            break;
        ++nbLibre;
    }
    std::cout << "Nombre de creneaux libres en fin d'annee: " << nbLibre << std::endl;
    setConfigInt("Nombre de creneaux libres en fin d'annee", nbLibre, resultat);

    return 0;
}