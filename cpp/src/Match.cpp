#include <Match.hpp>
#include <global.hpp>

std::map<unsigned int, Match> Match::m_byId;

Match::Match(const unsigned int poule, const unsigned int journee, Equipe* eq1, Equipe* eq2, Equipe* arb1, Equipe* arb2)
	: m_poule(poule)
	, m_journee(journee)
	, m_eq1(eq1)
	, m_eq2(eq2)
	, m_arb1(arb1)
	, m_arb2(arb2)
{
	static unsigned int ID = Match::GetIDMatch();
	m_byId[++ID] = *this;
	m_id = ID;
}

const Match* Match::byId(const unsigned int id)
{
	std::map<unsigned int, Match>::const_iterator it = m_byId.find(id);
	if (it == m_byId.end())
		return nullptr;

	const Match& out = it->second;
	return &out;
}

unsigned int Match::GetIDMatch()
{
	static const unsigned int MINID = SAISON * 10000u;
	if (Match::m_byId.empty() || Match::m_byId.crbegin()->first < MINID)
		return MINID;

	return Match::m_byId.crbegin()->first;
}