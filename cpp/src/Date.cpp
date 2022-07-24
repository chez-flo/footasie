#include <Date.hpp>
#include <sstream>

Date::Date(const std::string& str)
	:m_tm({ 0 })
	,m_isValid(false)
{
	parseStr(str);
}

Date::Date(const Date& date)
	:m_tm(date.m_tm)
	,m_isValid(date.m_isValid)
{

}

bool Date::operator==(const Date& date) const
{
	return m_tm.tm_year == date.m_tm.tm_year 
		&& m_tm.tm_mon == date.m_tm.tm_mon 
		&& m_tm.tm_mday == date.m_tm.tm_mday;
}

bool Date::operator!=(const Date& date) const
{
	return !operator==(date);
}

bool Date::operator<(const Date& date) const
{
	return m_tm.tm_year < date.m_tm.tm_year 
		|| (m_tm.tm_year == date.m_tm.tm_year && m_tm.tm_mon < date.m_tm.tm_mon) 
		|| (m_tm.tm_year == date.m_tm.tm_year && m_tm.tm_mon == date.m_tm.tm_mon && m_tm.tm_mday < date.m_tm.tm_mday);
}

bool Date::operator<=(const Date& date) const
{
	return operator<(date) || operator==(date);
}

bool Date::operator>(const Date& date) const
{
	return m_tm.tm_year > date.m_tm.tm_year
		|| (m_tm.tm_year == date.m_tm.tm_year && m_tm.tm_mon > date.m_tm.tm_mon)
		|| (m_tm.tm_year == date.m_tm.tm_year && m_tm.tm_mon == date.m_tm.tm_mon && m_tm.tm_mday > date.m_tm.tm_mday);
}

bool Date::operator>=(const Date& date) const
{
	return operator>(date) || operator==(date);
}

Date& Date::operator+(int jour)
{
	operator+=(jour);
	return *this;
}

void Date::operator+=(int jour)
{
	m_tm.tm_mday += jour;
	mktime(&m_tm);
}

Date& Date::operator-(int jour)
{
	operator+=(-jour);
	return *this;
}

void Date::operator-=(int jour)
{
	operator+=(-jour);
}

bool Date::isThisDay(const std::string& day) const
{
	switch (m_tm.tm_wday)
	{
	case 0:	return day == "dimanche";
	case 1:	return day == "lundi";
	case 2:	return day == "mardi";
	case 3:	return day == "mercredi";
	case 4:	return day == "jeudi";
	case 5:	return day == "vendredi";
	case 6:	return day == "samedi";
	default:break;
	}

	return false;
}

std::ostream& operator<<(std::ostream& stream, const Date& date)
{
	stream << date.m_tm.tm_mday << "/" << date.m_tm.tm_mon + 1 << "/" << date.m_tm.tm_year + 1900;
	return stream;
}

std::string Date::toStr() const
{
	std::ostringstream out;
	out << *this;
	return out.str();
}

std::string Date::toCSVLine() const
{
	std::ostringstream out;
	out << m_tm.tm_year + 1900 << "-" << m_tm.tm_mon + 1 << "-" << m_tm.tm_mday << " 12:30:00";
	return out.str();
}

void Date::parseStr(const std::string& str)
{
	m_isValid = false;
	if (!str.empty())
	{
		int year = 0, month = 0, day = 0;
		if (sscanf_s(str.c_str(), "%d/%d/%d", &day, &month, &year) == 3)
		{
			time_t raw;
			time(&raw);
			localtime_s(&m_tm, &raw);
			if (year >= 1900)
				m_tm.tm_year = year - 1900;
			else
				m_tm.tm_year = year;
			m_tm.tm_mon = month - 1;
			m_tm.tm_mday = day;
			mktime(&m_tm);

			m_isValid = true;
		}
		else if (sscanf_s(str.c_str(), "%d-%d-%d 12:30:00", &year, &month, &day) == 3)
		{
			time_t raw;
			time(&raw);
			localtime_s(&m_tm, &raw);
			if (year >= 1900)
				m_tm.tm_year = year - 1900;
			else
				m_tm.tm_year = year;
			m_tm.tm_mon = month - 1;
			m_tm.tm_mday = day;
			mktime(&m_tm);

			m_isValid = true;
		}
	}
}