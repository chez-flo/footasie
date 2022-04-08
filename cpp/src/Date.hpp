#ifndef DATE_HPP
#define DATE_HPP

#include <ctime>
#include <string>
#include <iostream>

class Date
{
public:
	Date();
	Date(const std::string& str);
	Date(const Date& date);
	~Date();

	bool operator==(const Date& date) const;
	bool operator!=(const Date& date) const;
	bool operator<(const Date& date) const;
	bool operator<=(const Date& date) const;
	bool operator>(const Date& date) const;
	bool operator>=(const Date& date) const;

	Date& operator+(int jour);
	void operator+=(int jour);
	Date& operator-(int jour);
	void operator-=(int jour);

	friend std::ostream& operator<<(std::ostream& stream, const Date& date);
	std::string toStr() const;
	bool isValid() const { return m_isValid; }
private:
	tm m_tm;
	bool m_isValid;

	void parseStr(const std::string& str);
};

#endif