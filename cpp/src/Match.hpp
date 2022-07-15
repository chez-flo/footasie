#ifndef MATCH_HPP
#define MATCH_HPP

#include <Date.hpp>
#include <Equipe.hpp>

class Match
{
public:
	Match() = default;
	Match(const unsigned int poule, const unsigned int journee, Equipe* eq1, Equipe* eq2, Equipe* arb1, Equipe* arb2);
	~Match() = default;

	void setEquipe1(Equipe* eq1) { m_eq1 = eq1; }
	void setEquipe2(Equipe* eq2) { m_eq2 = eq2; }
	void setArbitre1(Equipe* arb1) { m_arb1 = arb1; }
	void setArbitre2(Equipe* arb2) { m_arb2 = arb2; }

	unsigned int id() const { return m_id; }
	unsigned int journee() const { return m_journee; }
	Equipe* equipe1() const { return m_eq1; }
	Equipe* equipe2() const { return m_eq2; }
	Equipe* arbitre1() const { return m_arb1; }
	Equipe* arbitre2() const { return m_arb2; }

	static const Match* byId(const unsigned int id);
	
private:
	static std::map<unsigned int, Match> m_byId;

	unsigned int m_id = 0u;
	unsigned int m_poule = 0u;
	unsigned int m_journee = 0u;
	Equipe* m_eq1 = nullptr;
	Equipe* m_eq2 = nullptr;
	Equipe* m_arb1 = nullptr;
	Equipe* m_arb2 = nullptr;

	static unsigned int GetIDMatch();
};

#endif	//	MATCH_HPP