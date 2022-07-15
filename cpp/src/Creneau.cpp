#include <Creneau.hpp>
#include <global.hpp>
#include <iostream>
#include <fstream>
#include <sstream>

using namespace std;

std::vector<Creneau> Creneau::m_creneaux;

Creneau::Creneau(const Date& date, const int stade)
	: m_date(date)
	, m_stade(stade)
	, m_match(SAISON * 10000u)
	, m_isValid(date.isValid())
{
	m_creneaux.push_back(*this);
}

Creneau::Creneau(const Date& date, const int stade, const unsigned int match)
	: m_date(date)
	, m_stade(stade)
	, m_match(match)
	, m_isValid(date.isValid())
{
	m_creneaux.push_back(*this);
}

Creneau::~Creneau()
{

}

void Creneau::toCSV(const std::string& filename)
{
	fstream handle;
	handle.open(filename.c_str(), ios_base::out);

	// ecriture premiere ligne
	handle << "\"cre_id\",\"cre_date\",\"cre_mat_id\",\"cre_ter_id\"" << std::endl;

	// ecriture de chaque ligne
	for (std::vector<Creneau>::const_iterator it = m_creneaux.begin(); it != m_creneaux.end(); it++)
		if (it->isValid())
			handle << it->toCSVLine() << std::endl;
}

namespace {
	unsigned int findId(const string& line)
	{
		string::size_type f = line.find(",");
		if (f == string::npos || f >= line.length() || f < 3u)
			return 0u;

		const string subline = line.substr(0, f);
		if (subline.front() != '"' || subline.back() != '"')
			return 0u;

		return strtoul(subline.substr(1u, subline.length() - 1).c_str(), NULL, 10);
	}

	Date findDate(const string& line)
	{
		string::size_type d = line.find(",");
		string::size_type f = line.find(",", d + 1u);
		if (f == string::npos || f >= line.length() || f - d < 3u)
			return Date();

		const string subline = line.substr(d + 1u, f - d - 1u);
		if (subline.front() != '"' || subline.back() != '"')
			return Date();

		return Date(subline.substr(1u, subline.length() - 1));
	}

	unsigned int findMatch(const string& line)
	{
		string::size_type d = line.find(",");
		d = line.find(",", d + 1u);
		string::size_type f = line.find(",", d + 1u);
		if (f == string::npos || f >= line.length() || f - d < 3u)
			return 0u;

		const string subline = line.substr(d + 1u, f - d - 1u);
		if (subline.front() != '"' || subline.back() != '"')
			return 0u;

		return strtoul(subline.substr(1u, subline.length() - 1).c_str(), NULL, 10);
	}

	int findTerrain(const string& line)
	{
		string::size_type d = line.find(",");
		d = line.find(",", d + 1u);
		d = line.find(",", d + 1u);

		const string subline = line.substr(d + 1u);
		if (subline.front() != '"' || subline.back() != '"')
			return 0;

		return strtol(subline.substr(1u, subline.length() - 1).c_str(), NULL, 10);
	}
}

void Creneau::fromCSV(const std::string& filename)
{
	fstream handle;
	handle.open(filename.c_str(), ios_base::in);

	// lecture premiere ligne
	string line;
	if (!handle.eof() && handle.is_open())
		getline(handle, line);

	// lecture de toutes les lignes
	while (!handle.eof() && handle.is_open())
	{
		// les 4 1ers champs nous interessent
		getline(handle, line);

		const Date date = findDate(line);
		if (date.isValid())
			Creneau c(date, findTerrain(line), findMatch(line));
	}
}

std::string Creneau::toCSVLine() const
{
	static const unsigned int FREEID = SAISON * 10000u;

	ostringstream out;
	out << "\"" << (int)m_id
		<< "\",\"" << m_date.toCSVLine()
		<< "\",\"" << (int)(m_match ? m_match : FREEID)
		<< "\",\"" << m_stade
		<< "\"";

	return out.str();
}