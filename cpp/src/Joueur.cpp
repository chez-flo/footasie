#include <Joueur.hpp>
#include <iostream>
#include <fstream>
#include <sstream>
#include <algorithm>
#include <cctype>

using namespace std;

map<std::string, Joueur> Joueur::m_byMail;
map<std::string, Joueur> Joueur::m_byNom;

namespace {
	string trim(const string& name)
	{
		string out = name;
		out.erase(out.find_last_not_of(" \n\r\t") + 1);
		return out;
	}

	string tolower(const string& name)
	{
		string s = name;
		std::transform(s.begin(), s.end(), s.begin(),
			[](unsigned char c) { return std::tolower(c); }
		);
		return s;
	}

	string camelCase(const string& name)
	{
		bool active = true;
		string s = name;

		std::transform(s.begin(), s.end(), s.begin(),
			[&active](unsigned char c) 
			{ 
				unsigned char out = active ? std::toupper(c) : std::tolower(c);
				active = (c == ' ') || (c == '-');
				return out;
			}
		);
		return s;
	}

	int findId(const string& line)
	{
		string::size_type f = line.find(",");
		if (f == string::npos || f >= line.length() || f < 3u)
			return 0u;

		const string subline = line.substr(0, f);
		if (subline.front() != '"' || subline.back() != '"')
			return 0u;

		return strtoul(subline.substr(1u, subline.length() - 1).c_str(), NULL, 10);
	}

	string findNom(const string& line)
	{
		string::size_type d = line.find(",");
		string::size_type f = line.find(",", d + 1u);
		if (f == string::npos || f >= line.length() || f - d < 3u)
			return "";

		const string subline = line.substr(d + 1u, f - d - 1u);
		if (subline.front() != '"' || subline.back() != '"')
			return "";

		return camelCase(trim(subline.substr(1u, subline.length() - 2u)));
	}

	string findMail(const string& line)
	{
		string::size_type d = line.find(",");
		d = line.find(",", d + 1u);
		string::size_type f = line.find(",", d + 1u);
		if (f == string::npos || f >= line.length() || f - d < 3u)
			return "";

		const string subline = line.substr(d + 1u, f - d - 1u);
		if (subline.front() != '"' || subline.back() != '"')
			return "";

		return trim(subline.substr(1u, subline.length() - 2u));
	}

	int findInscState(const string& line)
	{
		string::size_type d = line.find(",");
		d = line.find(",", d + 1u);
		d = line.find(",", d + 1u);
		d = line.find(",", d + 1u);
		d = line.find(",", d + 1u);
		d = line.find(",", d + 1u);
		string::size_type f = line.find(",", d + 1u);
		if (f == string::npos || f >= line.length() || f - d < 3u)
			return 0;

		const string subline = line.substr(d + 1u, f - d - 1u);
		if (subline.front() != '"' || subline.back() != '"')
			return 0;

		return strtol(subline.substr(1u, subline.length() - 1).c_str(), NULL, 10);
	}
}


Joueur::Joueur(const unsigned int id, const string& name, const string& mail, const int insc_state)
	: m_id(id)
	, m_name(name)
	, m_mail(mail)
	, m_insc_state(insc_state)
{
	m_byMail[tolower(trim(m_mail))] = *this;
	m_byNom[tolower(trim(m_name))] = *this;
}

void Joueur::readCSV(const string& filename)
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

		const unsigned int id = findId(line);
		const string name = findNom(line);
		const string mail = findMail(line);
		const int insc_state = findInscState(line);
		Joueur j(id, name, mail, insc_state);
	}
}

Joueur* Joueur::byMail(const std::string& mail)
{
	std::map<std::string, Joueur>::iterator it = m_byMail.find(tolower(trim(mail)));
	if (it == m_byMail.end())
		return nullptr;

	return &it->second;
}

Joueur* Joueur::byNom(const std::string& nom)
{
	std::map<std::string, Joueur>::iterator it = m_byNom.find(tolower(trim(nom)));
	if (it == m_byNom.end())
		return nullptr;

	return &it->second;
}