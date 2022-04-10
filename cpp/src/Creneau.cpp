#include "Creneau.hpp"

Creneau::Creneau(const Date& date, const int stade, const Match* pMatch)
	: m_date(date)
	, m_stade(stade)
	, m_pMatch(pMatch)
	, m_isValid(date.isValid())
{

}

Creneau::~Creneau()
{

}