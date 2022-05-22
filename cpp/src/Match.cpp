#include "Match.hpp"

Match::Match(const int journee, Equipe* eq1, Equipe* eq2, Equipe* arb1, Equipe* arb2)
	:m_journee(journee)
	, m_eq1(eq1)
	, m_eq2(eq2)
	, m_arb1(arb1)
	, m_arb2(arb2)
{
}