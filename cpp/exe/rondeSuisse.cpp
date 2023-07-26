#include <iostream>
#include <fstream>
#include <map>
#include <algorithm>
#include <random>
#include <getConfig.hpp>

using Equipe = std::string;
using Equipes = std::vector<Equipe>;

struct Rencontre
{
    int points = 0;
    Equipes adversaires;
};
using Rencontres = std::map<std::string, Rencontre>;

Rencontres getRencontres(const Equipes& equipes, const std::string& config)
{
    // base de requetes
    const std::string baseQueryPoints = "Tournoi suisse - points de ";
    const std::string baseQueryAdversaires = "Tournoi suisse - adversaires de ";
    // sortie
    Rencontres all;
    // recuperation des infos
    for (const std::string& equipe : equipes)
    {
        const std::string queryPoints = baseQueryPoints + equipe;
        all[equipe].points = getConfigAsInt(queryPoints, 0, config);
        const std::string queryRencontres = baseQueryAdversaires + equipe;
        all[equipe].adversaires = getConfigAsVectorString(queryRencontres, {}, config);
    }
    // return
    return all;
}

using Match = std::pair<std::string, std::string>;
using Matchs = std::vector<Match>;
using Niveaux = std::map<int, Equipes, std::greater<int>>;

bool hasPlayedTogether(const Equipe& local, const Equipe& visiteur, const Rencontres& rencontres)
{
    const Rencontre& rencontre = rencontres.at(local);
    return std::find(rencontre.adversaires.begin(), rencontre.adversaires.end(), visiteur) != rencontre.adversaires.end();
}

bool tireMatchs(Matchs &matchs, const Equipes &equipes, const Rencontres &rencontres)
{
    // si plus d'equipes, c'est termine !
    if (equipes.empty())
        return true;
    // equipes restantes
    Equipes reste = equipes;
    // equipe locale
    Equipe local = equipes.front();
    reste.erase(reste.begin());
    // parcours des equipes restantes
    for (Equipe& visiteur : reste)
    {
        // test si deja joue ensemble
        if (hasPlayedTogether(local, visiteur, rencontres))
            continue;

        // on teste le tirage avec cette rencontre
        // on retire donc cette equipe de la liste
        Equipes tmp = reste;
        tmp.erase(std::find(tmp.begin(), tmp.end(), visiteur));
        // et on teste la suite du tirage
        if (tireMatchs(matchs, tmp, rencontres))
        {
            // si ok, on ajoute la rencontre et return ok
            matchs.push_back(std::make_pair(local, visiteur));
            return true;
        }
    }

    return false;
}

Matchs getProchainsMatchs(const Equipes &equipes, const Rencontres &rencontres)
{
    // rangement des equipes par groupes de memes niveaux
    Niveaux niveaux;
    for (const Equipe& equipe : equipes)
    {
        const Rencontre& rencontre = rencontres.at(equipe);
        niveaux[rencontre.points].push_back(equipe);
    }
        
    // transformation en vecteur d'equipes ordonnees selon classement
    Equipes classement;
    for (const auto& it : niveaux)
        for (const Equipe& equipe : it.second)
            classement.push_back(equipe);

    // tirage des matchs selon classement
    Matchs out;
    if (!tireMatchs(out, classement, rencontres))
        std::cout << "Probleme tirage... " << std::endl;

    return out;
}

void saveResultatAndUpdateConfig(const Matchs& matchs, const std::string& file, const std::string& config)
{
    std::fstream h(file, std::ios::out);
    if (h)
    {
        const std::string baseQueryAdversaires = "Tournoi suisse - adversaires de ";
        for (const Match& match : matchs)
        {
            // sauvegarde match
            h << match.first << " - " << match.second << std::endl;
            // update config equipe 1
            std::string queryAdversaires = baseQueryAdversaires + match.first;
            Equipes adversaires = getConfigAsVectorString(queryAdversaires, {}, config);
            adversaires.push_back(match.second);
            setConfigVectorString(queryAdversaires, adversaires, config);
            // update config equipe 
            queryAdversaires = baseQueryAdversaires + match.second;
            adversaires = getConfigAsVectorString(queryAdversaires, {}, config);
            adversaires.push_back(match.first);
            setConfigVectorString(queryAdversaires, adversaires, config);
        }
    }
}

int main(int argc, char** argv)
{
    // fichier .ini
    std::string config = "config.ini";
    if (argc > 1)
        config = argv[1];
    // recuperation liste d'equipes
    Equipes equipes = getConfigAsVectorString("Tournoi suisse - liste equipes", {}, config);
    if (equipes.size() % 2u > 0u)   equipes.push_back("NULL");      // si nombre d'equipes impair
    // recuperation rencontres passees
    Rencontres rencontres = getRencontres(equipes, config);
    // recuperation prochains matchs
    std::random_device rd;
    std::mt19937 g(rd());
    std::shuffle(std::begin(equipes), std::end(equipes), g);
    Matchs prochainsMatchs = getProchainsMatchs(equipes, rencontres);
    // resultats
    std::string resultat = getConfigAsString("Tournoi suisse - fichier matchs", "data/Match-suisse.ini", config);
    saveResultatAndUpdateConfig(prochainsMatchs, resultat, config);

    return 0;
}