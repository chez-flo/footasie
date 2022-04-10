#ifndef CRENEAU_HPP
#define CRENEAU_HPP

#include "Date.hpp"
#include "Match.hpp"

class Creneau
{
public:
	Creneau(const Date &date, const int stade, const Match *pMatch);
	~Creneau();

	const Date& date() const { return m_date; }
	int stade() const { return m_stade; }
	const Match* match() const { return m_pMatch; }
	bool isValid() const { return m_isValid; }
private:
	Date m_date;
	int m_stade;
	const Match* m_pMatch;
	bool m_isValid;
};

#endif	//	CRENEAU_HPP