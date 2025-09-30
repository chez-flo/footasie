#ifndef POULE_HPP
#define POULE_HPP

#include <Equipe.hpp>
#include <Match.hpp>
#include <vector>

class Poule
{
public:
	Poule() = default;
	~Poule() = default;

	// getters
	const std::vector<Equipe*>& equipes() const { return m_equipe; }

	// setters
	void setIdPoule(const unsigned int id) { m_id = id; }
	void setIdArbitre(const std::vector<unsigned int> &id) { m_arb = id; }
	void addIdArbitre(const unsigned int id) { m_arb.push_back(id); }

	// getters
	unsigned int getIdPoule() const { return m_id; }
	const std::vector<unsigned int> &getIdArbitre() const { return m_arb; }
	unsigned int getFirstIdArbitre() const { return m_arb.front(); }

	// adders
	void addEquipe(const std::string& name);

	// tester
	bool cetteEquipePeutArbitrer(const Equipe& eq) const {
		return std::find(m_arb.cbegin(), m_arb.cend(), eq.poule()) != m_arb.cend();
	}
	
	// generation des matchs
	void genereMatchs(const bool genereRetours);

private:
	unsigned int m_id = 0u;
	std::vector<unsigned int> m_arb;

	std::vector<Equipe*> m_equipe;

	std::vector<Match> m_match;

	// methodes usuelles
	bool ontIlsDejaJoue(const Equipe* eq1, const Equipe* eq2) const;
};

#endif	// POULE_HPP