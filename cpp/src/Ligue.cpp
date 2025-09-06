#include <Ligue.hpp>

#include <random>
#include <algorithm>

void Ligue::addEquipe(const std::string& name)
{
	Equipe* equipe = Equipe::byName(name);
	if (equipe && equipe->isValid())
		m_equipe.push_back(equipe);
}

namespace
{
	template<typename T>
	std::vector<T> melange(const std::vector<T>& in)
	{
		static std::random_device rd;
		static std::default_random_engine gen(rd());
		std::vector<T> cpy = in;
		std::shuffle(std::begin(cpy), std::end(cpy), gen);
		return cpy;
	}

	std::vector<std::vector<int> > genereTable(const int nbEquipes)
	{
		// alloc
		std::vector<std::vector<int> > out = std::vector<std::vector<int> >((unsigned int)nbEquipes - 1u, std::vector<int>(nbEquipes, -1));

		// remplissage circulaire des nbEquipes-1u
		for (int e = 0; e < nbEquipes - 1; ++e)
		{
			for (int j = 0; j < nbEquipes - 1; ++j)
				out[(j + e) % (nbEquipes - 1)][e] = j;
		}

		// corrections + remplissage derniere equipe
		for (int e = 0; e < nbEquipes - 1; ++e)
		{
			for (int j = 0; j < nbEquipes - 1; ++j)
			{
				if (out[j][e] == e)
				{
					out[j][e] = nbEquipes - 1;
					out[j][nbEquipes - 1] = e;
					break;
				}
			}
		}

		return out;
	}
}

void Ligue::genereMatchs(const std::vector<std::vector<std::string>>& chapeaux, const bool joueTonChapeau)
{
	// on genere une file d'equipes dans laquelle on piochera les arbitres au fur et à mesure
	std::vector<std::string> arbitres;
	for (const auto& chapeau : chapeaux)
		arbitres.insert(arbitres.end(), chapeau.begin(), chapeau.end());
	arbitres = melange(arbitres);

	// on commence par generer les types de journees : chapeau 1 vs chapeau 2, etc...
	// on harmonise le nombre de chapeaux
	const int nbChapeaux = chapeaux.size() % 2u == 0u ? (int)chapeaux.size() : (int)chapeaux.size() + 1;
	// nombre de journees
	const int nbJournees = nbChapeaux - 1;
	// generation de la table d'elaboration du championnat par chapeau
	const auto table = genereTable(nbChapeaux);

	// on a donc les journees typiques
	// pour chaque journee, on genere les matchs typiques associes
	for (int j = 0; j < nbJournees; ++j)
	{
		for (int c1 = 0; c1 < nbChapeaux; ++c1)
		{
			// recuperation du chapeau jouant en face cette journee
			const int c2 = table[j][c1];
			// test des chapeaux
			if (c1 >= (int)chapeaux.size() || c2 >= (int)chapeaux.size())	continue;
			// recuperation et melange des equipes
			const std::vector<std::string> chapeau1 = melange(chapeaux[c1]);
			const std::vector<std::string> chapeau2 = melange(chapeaux[c2]);
			// on part du principe que tous les chapeaux ont le meme nombre d'equipes
			for (int e = 0; e < (int)chapeau1.size(); ++e)
			{
				// recuperation des equipes
				Equipe* eq1 = Equipe::byName(chapeau1[e]);
				Equipe* eq2 = Equipe::byName(chapeau2[e]);
				// determination de l'arbitre
				std::vector<std::string>::const_iterator it = arbitres.begin();
				for (; *it == eq1->nom() || *it == eq2->nom(); it++);
				Equipe* arb = Equipe::byName(*it);
				// ajout du match
				if (!ontIlsDejaJoueCeChapeau(eq1, eq2))
				{
					m_match.push_back(Match(m_id, j + 1, eq1, eq2, arb, nullptr));
					// update vecteur d'arbitre
					arbitres.erase(it);
					arbitres.push_back(arb->nom());
				}
			}
		}
	}

	// journee ou tu joues contre les equipes de ton chapeau
	if (joueTonChapeau)
	{
		for (int c = 0; c < chapeaux.size(); ++c)
		{
			// melange les equipes du chapeau
			const std::vector<std::string> chapeau = melange(chapeaux[c]);
			// puis fait se rencontrer les equipes (la 1ere contre la derniere, etc...)
			for (int e = 0; e < (int)chapeau.size()/2; ++e)
			{
				// recuperation des equipes
				Equipe* eq1 = Equipe::byName(chapeau[e]);
				Equipe* eq2 = Equipe::byName(chapeau[(int)chapeau.size()-e-1]);
				// determination de l'arbitre
				std::vector<std::string>::const_iterator it = arbitres.begin();
				for (; *it == eq1->nom() || *it == eq2->nom(); it++);
				Equipe* arb = Equipe::byName(*it);
				// ajout du match
				if (!ontIlsDejaJoueCeChapeau(eq1, eq2))
				{
					m_match.push_back(Match(m_id, nbJournees + 1, eq1, eq2, arb, nullptr));
					// update vecteur d'arbitre
					arbitres.erase(it);
					arbitres.push_back(arb->nom());
				}
			}
		}
	}
}

bool Ligue::ontIlsDejaJoueCeChapeau(const Equipe* eq1, const Equipe* eq2) const
{
	for (const Match& match : m_match)
	{
		if (eq1->id() == match.equipe1()->id() && eq2->chapeau() == match.equipe2()->chapeau())
			return true;
		if (eq1->id() == match.equipe2()->id() && eq2->chapeau() == match.equipe1()->chapeau())
			return true;
	}
	return false;
}