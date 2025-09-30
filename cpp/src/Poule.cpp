#include <Poule.hpp>

#include <random>
#include <algorithm>
#include <melange.hpp>

void Poule::addEquipe(const std::string& name)
{
	Equipe* equipe = Equipe::byName(name);
	if (equipe && equipe->isValid())
	{
		m_equipe.push_back(equipe);
		equipe->setPoule(m_id);
	}
}

namespace
{
	std::vector<std::vector<int> > genereTable(const int nbEquipes)
	{
		// alloc
		std::vector<std::vector<int> > out = std::vector<std::vector<int> >((unsigned int)nbEquipes-1u, std::vector<int>(nbEquipes, -1));

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

	std::map<Equipe*, unsigned int> constructMapArbitrage(const std::vector<Equipe*>& equipe)
	{
		std::map<Equipe*, unsigned int> out;
		for (const auto& val : equipe)	out[val] = 0u;
		return out;
	}

	Equipe* getLessArbitre(std::map<Equipe*, unsigned int>& arbitre, const Poule &poule)
	{
		//*///
		unsigned int nbArbitrages = 9999u;
		std::vector<Equipe*> canArbitre;
		// recherche les equipes qui peuvent arbitrer
		for (std::map<Equipe*, unsigned int>::const_iterator it = arbitre.begin(); it != arbitre.end(); it++)
		{
			if (poule.cetteEquipePeutArbitrer(*(it->first)))
			{
				if (it->second < nbArbitrages)	// si cette equipe a moins d'arbitrage que les autres
				{
					canArbitre.clear();
					canArbitre.push_back(it->first);
					nbArbitrages = it->second;
				}
				else if (it->second == nbArbitrages)
					canArbitre.push_back(it->first);
			}
		}
		// on melange pour avoir une saisie aleatoire parmis les grands vainqueurs
		Equipe* out = melange(canArbitre).front();
		++arbitre[out];
		return out;
		/*///
		// recherche 1ere equipe qui peut arbitrer
		std::map<Equipe*, unsigned int>::iterator sel = arbitre.begin();
		for (; !poule.cetteEquipePeutArbitrer(*(sel->first)) && sel != arbitre.end(); sel++);

		// parcours de toutes les equipes pour prendre celle avec le moins d'arbitrage qui peut arbitrer
		for (std::map<Equipe*, unsigned int>::iterator it = arbitre.begin(); it != arbitre.end(); it++)
		{
			if ((it->second < sel->second) && (poule.cetteEquipePeutArbitrer(*(it->first))))
				sel = it;
		}

		// incremente le nombre d'arbitrages
		++(sel->second);
		return sel->first;
		//*///
	}
}

void Poule::genereMatchs(const bool genereRetours)
{
	// liste des equipes de base
	std::vector<Equipe*> equipes = melange(m_equipe);

	// liste de toutes les equipes qui jouent, permet d'avoir un pool global d'arbitres
	static std::map<Equipe*, unsigned int> arbitres = constructMapArbitrage(Equipe::getAllPlayingEquipe());

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
	for (int j = 0; j < nbJournees; ++j)
	{
		// melange des equipes
		std::vector<int> newId = melange(id);
		// ajout des matchs
		for (int e = 0; e < (int)equipes.size(); ++e)
		{
			Equipe* eq1 = equipes[newId[e]];
			Equipe* eq2 = equipes[table[j][newId[e]]];
			//*///
			if (!ontIlsDejaJoue(eq1, eq2))
			{
				Equipe* arb = (eq1 == nullptr || eq2 == nullptr) ? nullptr : getLessArbitre(arbitres, *this);
				m_match.push_back(Match(m_id, j+1, eq1, eq2, arb, nullptr));
			}
			/*///
			Equipe* arb = (eq1 == nullptr || eq2 == nullptr) ? nullptr : arbitres[idArb];
			if (!ontIlsDejaJoue(eq1, eq2))
			{
				m_match.push_back(Match(m_id, j+1, eq1, eq2, arb, nullptr));
				if (arb != nullptr)
					idArb = (idArb + 1) % (int)arbitres.size();
			}
			//*///
		}
	}

	// suppression des matchs inutiles
	for (std::vector<Match>::const_iterator it = m_match.begin(); it != m_match.end(); it = (it->arbitre1() == nullptr) ? m_match.erase(it) : it + 1);

	// generation de la phase retour
	if (genereRetours) 
	{
		const int nbMatchsAller = (int)m_match.size();
		for (int n = 0; n < nbMatchsAller; ++n)
		{
			Equipe* eq1 = m_match[n].equipe2();
			Equipe* eq2 = m_match[n].equipe1();
			//*///
			Equipe* arb = getLessArbitre(arbitres, *this);
			m_match.push_back(Match(m_id, m_match[n].journee() + nbJournees, eq1, eq2, arb, nullptr));
			/*///
			Equipe* arb = arbitres[idArb];
			m_match.push_back(Match(m_id, m_match[n].journee() + nbJournees, eq1, eq2, arb, nullptr));
			idArb = (idArb + 1) % (int)arbitres.size();
			//*///
		}
	}


	// affichage arbitrage
	std::cout << "Repartition arbitrages apres poule " << getIdPoule() << std::endl;
	for (const auto& val : arbitres)	std::cout << "\t" << "Poule " << val.first->poule() << " - " << val.first->nom() << ": " << val.second << std::endl;
	std::cout << std::endl;
}

bool Poule::ontIlsDejaJoue(const Equipe* eq1, const Equipe* eq2) const
{
	const unsigned int id1 = eq1 ? eq1->id() : 0u;
	const unsigned int id2 = eq2 ? eq2->id() : 0u;
	for (const Match& match : m_match)
	{
		const unsigned int jd1 = match.equipe1() ? match.equipe1()->id() : 0u;
		const unsigned int jd2 = match.equipe2() ? match.equipe2()->id() : 0u;
		if (jd1 == id1 && jd2 == id2)
			return true;
		if (jd1 == id2 && jd2 == id1)
			return true;
	}
	return false;
}