#include <Equipe.hpp>
#include <iostream>
#include <fstream>
#include <sstream>

using namespace std;

map<std::string, Equipe> Equipe::m_byName;

Equipe::Equipe(const unsigned int id, const std::string& name, const unsigned int terrain, const std::string entrainement)
	: m_id(id)
	, m_name(name)
	, m_terrain(terrain)
	, m_entrainement(entrainement)
	, m_isValid(true)
{
	m_byName[m_name] = *this;
}

Equipe::Equipe(const Equipe& eq)
	: m_id(eq.m_id)
	, m_name(eq.m_name)
	, m_terrain(eq.m_terrain)
	, m_entrainement(eq.m_entrainement)
	, m_isValid(eq.m_isValid)
{
	m_byName[m_name] = *this;
}

bool Equipe::operator==(const Equipe& eq) const
{
	return m_isValid && m_id == eq.m_id;
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

		unsigned int out = 0u;
		return strtoul(subline.substr(1u, subline.length()-1).c_str(), NULL, 10);
	}

	string findName(const string& line)
	{
		string::size_type d = line.find(",");
		string::size_type f = line.find(",", d + 1u);
		if (f == string::npos || f >= line.length() || f-d < 3u)
			return "";

		const string subline = line.substr(d + 1u, f-d-1u);
		if (subline.front() != '"' || subline.back() != '"')
			return "";

		return subline.substr(1u, subline.length() - 2u);
	}

	unsigned int findTerrain(const string& line)
	{
		string::size_type d = line.find(",");
		d = line.find(",", d + 1u);
		string::size_type f = line.find(",", d + 1u);
		if (f == string::npos || f >= line.length() || f-d < 3u)
			return 0u;

		const string subline = line.substr(d + 1u, f-d-1u);
		if (subline.front() != '"' || subline.back() != '"')
			return 0u;

		unsigned int out = 0u;
		return strtoul(subline.substr(1u, subline.length() - 1).c_str(), NULL, 10);
	}

	string findEntrainement(const string& line)
	{
		string::size_type d = line.find(",");
		d = line.find(",", d + 1u);
		d = line.find(",", d + 1u);
		string::size_type f = line.find(",", d + 1u);
		if (f == string::npos || f >= line.length() || f-d < 3u)
			return "";

		const string subline = line.substr(d + 1u, f-d-1u);
		if (subline.front() != '"' || subline.back() != '"')
			return "";

		return subline.substr(1u, subline.length() - 2u);
	}
}

void Equipe::readCSV(const std::string& filename)
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
		
		const string name = findName(line);
		m_byName[name] = Equipe(findId(line), name, findTerrain(line), findEntrainement(line));
	}
}

Equipe& Equipe::byName(const std::string& name)
{
	return m_byName[name];
}
