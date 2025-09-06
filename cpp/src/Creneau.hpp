#ifndef CRENEAU_HPP
#define CRENEAU_HPP

#include <Date.hpp>
#include <Match.hpp>

#include <vector>

class Creneau
{
public:
	Creneau(const Date& date, const int stade);
	Creneau(const Date &date, const int stade, const unsigned int match);
	~Creneau();

	const Date& date() const { return m_date; }
	int stade() const { return m_stade; }
	unsigned int match() const { return m_match; }
	void setMatch(const Match& match) { m_match = match.id(); }
	bool isValid() const { return m_isValid; }

	static std::vector<Creneau>& getCreneaux() { return m_creneaux; }
	static void clean(const Date& debut);
	static void toCSV(const std::string& filename);
	static void fromCSV(const std::string& filename);
	static void toSql(const std::string& filename);
	static void updateSql(const std::string& filename);

	static std::vector<Creneau> getCreneauxFromDate(const Date& date);

private:
	static std::vector<Creneau> m_creneaux;

	unsigned int m_id = 0u;
	Date m_date;
	int m_stade;
	unsigned int m_match;
	bool m_isValid;

	std::string toSqlLine() const;
	std::string toCSVLine() const;
};

#endif	//	CRENEAU_HPP