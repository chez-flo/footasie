#ifndef MATCH_HPP
#define MATCH_HPP

#include <Date.hpp>
#include <Equipe.hpp>

#include <vector>

class Match
{
public:
	Match();
	Match(const unsigned int poule, const unsigned int journee, Equipe* eq1, Equipe* eq2, Equipe* arb1, Equipe* arb2);
	Match(const unsigned int poule, const unsigned int journee, Equipe* eq1, Equipe* eq2, Equipe* arb1, Equipe* arb2, unsigned int id);
	~Match() = default;

	void setEquipe1(Equipe* eq1) { m_eq1 = eq1 ? eq1->id() : 0u; }
	void setEquipe2(Equipe* eq2) { m_eq2 = eq2 ? eq2->id() : 0u; }
	void setArbitre1(Equipe* arb1) { m_arb1 = arb1 ? arb1->id() : 0u; }
	void setArbitre2(Equipe* arb2) { m_arb2 = arb2 ? arb2->id() : 0u; }

	unsigned int id() const { return m_id; }
	unsigned int journee() const { return m_journee; }
	Equipe* equipe1() const { return Equipe::byId(m_eq1); }
	Equipe* equipe2() const { return Equipe::byId(m_eq2); }
	Equipe* arbitre1() const { return Equipe::byId(m_arb1); }
	Equipe* arbitre2() const { return Equipe::byId(m_arb2); }

	bool isThisEquipePlaying(const Equipe* equipe) const;
	bool isThisEquipeParticipating(const Equipe* equipe) const;

	static void clear();
	static const Match* byId(const unsigned int id);
	static void toCSV(const std::string& filename);
	static void fromCSV(const std::string& filename);
	static void toSql(const std::string& filename);

	static const std::map<unsigned int, Match>& getMatch() { return m_byId; }
	static std::vector<Match> getJournee(const unsigned int journee);
	
private:
	static std::map<unsigned int, Match> m_byId;

	unsigned int m_id = 0u;
	unsigned int m_poule = 0u;
	unsigned int m_journee = 0u;
	unsigned int m_eq1 = 0u;
	unsigned int m_eq2 = 0u;
	unsigned int m_arb1 = 0u;
	unsigned int m_arb2 = 0u;

	std::string toCSVLine() const;
	static unsigned int GetIDMatch();
};

#endif	//	MATCH_HPP