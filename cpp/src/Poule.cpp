#include <Poule.hpp>

#include <random>
#include <algorithm>

void Poule::addEquipe(const std::string& name)
{
	const Equipe& equipe = Equipe::byName(name);
	if (equipe.isValid())
		m_equipe.push_back(equipe);
}

void Poule::addArbitre(const std::string& name)
{
	const Equipe& equipe = Equipe::byName(name);
	if (equipe.isValid())
		m_arbitre.push_back(equipe);
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
		std::vector<std::vector<int> > out = std::vector<std::vector<int> >(nbEquipes-1u, std::vector<int>(nbEquipes, -1));

		// remplissage circulaire des nbEquipes-1u
		for (int e = 0; e < nbEquipes-1; ++e)
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

void Poule::genereMatchs()
{
	// liste des equipes de base
	std::vector<Equipe*> equipes;
	for (Equipe& eq : m_equipe)
		equipes.push_back(&eq);
	std::vector<Equipe*> arbitres;
	for (Equipe& eq : m_arbitre)
		arbitres.push_back(&eq);
	// gestion du nombre impair d'equipes
	if ((int)equipes.size() % 2 == 1)
		equipes.push_back(nullptr);
	// liste d'id qui sera melangee
	std::vector<int> id;
	for (int n = 0; n < (int)equipes.size(); ++n)
		id.push_back(n);

	// generation de la phase aller (les matchs retours seront l'inverse des matchs aller)
	// nombre de journees
	const int nbJournees = (int)equipes.size() - 1;
	// generation de la table d'elaboration du championnat
	const auto table = genereTable((int)equipes.size());
	// generation des journees
	int idArb = 0;
	for (int j = 0; j < nbJournees; ++j)
	{
		// melange des equipes
		std::vector<int> newId = melange(id);
		// ajout des matchs
		for (int e = 0; e < (int)equipes.size(); ++e)
		{
			Equipe* eq1 = equipes[newId[e]];
			Equipe* eq2 = equipes[table[j][newId[e]]];
			Equipe* arb = (eq1 == nullptr || eq2 == nullptr) ? nullptr : arbitres[idArb];
			if (!ontIlsDejaJoue(eq1, eq2))
			{
				m_match.push_back(Match(j+1, eq1, eq2, arb, nullptr));
				if (arb != nullptr)
					idArb = (idArb + 1) % (int)arbitres.size();
			}
		}
	}

	// suppression des matchs inutiles
	for (std::vector<Match>::const_iterator it = m_match.begin(); it != m_match.end(); it = (it->arbitre1() == nullptr) ? m_match.erase(it) : it + 1);

	// generation de la phase retour
	const int nbMatchsAller = (int)m_match.size();
	for (int n = 0; n < nbMatchsAller; ++n)
	{
		Equipe* eq1 = m_match[n].equipe2();
		Equipe* eq2 = m_match[n].equipe1();
		Equipe* arb = arbitres[idArb];
		m_match.push_back(Match(m_match[n].journee() + nbJournees, eq1, eq2, arb, nullptr));
		idArb = (idArb + 1) % (int)arbitres.size();
	}
}

bool Poule::ontIlsDejaJoue(const Equipe* eq1, const Equipe* eq2) const
{
	for (const Match& match : m_match)
	{
		if (match.equipe1() == eq1 && match.equipe2() == eq2)
			return true;
		if (match.equipe1() == eq2 && match.equipe2() == eq1)
			return true;
	}
	return false;
}