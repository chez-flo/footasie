#include <Match.hpp>
#include <global.hpp>
#include <iostream>
#include <fstream>
#include <sstream>

using namespace std;

std::map<unsigned int, Match> Match::m_byId;

Match::Match()
{
	m_id = Match::GetIDMatch();
	m_byId.insert({ m_id, *this });
}

Match::Match(const unsigned int poule, const unsigned int journee, Equipe* eq1, Equipe* eq2, Equipe* arb1, Equipe* arb2)
	: m_poule(poule)
	, m_journee(journee)
	, m_eq1(eq1 ? eq1->id() : 0u)
	, m_eq2(eq2 ? eq2->id() : 0u)
	, m_arb1(arb1 ? arb1->id() : 0u)
	, m_arb2(arb2 ? arb2->id() : 0u)
{
	m_id = Match::GetIDMatch();
	m_byId.insert({ m_id, *this });
}

Match::Match(const unsigned int poule, const unsigned int journee, Equipe* eq1, Equipe* eq2, Equipe* arb1, Equipe* arb2, const unsigned int id)
	: m_poule(poule)
	, m_journee(journee)
	, m_eq1(eq1 ? eq1->id() : 0u)
	, m_eq2(eq2 ? eq2->id() : 0u)
	, m_arb1(arb1 ? arb1->id() : 0u)
	, m_arb2(arb2 ? arb2->id() : 0u)
{
	m_id = id;
	m_byId.insert({ m_id, *this });
}

const Match* Match::byId(const unsigned int id)
{
	std::map<unsigned int, Match>::const_iterator it = m_byId.find(id);
	if (it == m_byId.end())
		return nullptr;

	const Match& out = it->second;
	return &out;
}

void Match::toCSV(const std::string& filename)
{
	fstream handle;
	handle.open(filename.c_str(), ios_base::out);

	// ecriture premiere ligne
	handle << "\"mat_id\", \"mat_eq_id_1\", \"mat_eq_id_2\", \"mat_eq_id_3\", \"mat_eq_id_4\", \"mat_journee\", \"mat_statut\", \"mat_pou_id\", \"mat_sai_annee\", \"mat_commentaire\"" << std::endl;

	// ecriture match amical de l'annee
	handle << "\"" << (int)(SAISON * 10000u) << "\",\"1\",NULL,NULL,NULL,NULL,\"0\",\"1\",\"" << (int)SAISON << "\"," << std::endl;

	// ecriture de chaque ligne
	for (std::map<unsigned int, Match>::const_iterator it = m_byId.begin(); it != m_byId.end(); it++)
		if (it->second.equipe1() && it->second.equipe2())
			handle << it->second.toCSVLine() << std::endl;
}

namespace {
	unsigned int findUInt(const string& line, const unsigned int id)
	{
		string::size_type d = id == 0u ? 0u : line.find(",");
		for (unsigned int n=1u; n<id; ++n)
			d = line.find(",", d + 1u);
		string::size_type f = line.find(",", d + 1u);
		if (f == string::npos || f >= line.length() || f - d < 3u)
			return 0u;

		const string subline = d == 0u ? line.substr(0u, f) : line.substr(d + 1u, f - d - 1u);
		if (subline.front() != '"' || subline.back() != '"')
			return 0u;

		return strtoul(subline.substr(1u, subline.length() - 1).c_str(), NULL, 10);
	}
}

void Match::fromCSV(const std::string& filename)
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
		getline(handle, line);

		Equipe* eq1 = Equipe::byId(findUInt(line, 1u));
		Equipe* eq2 = Equipe::byId(findUInt(line, 2u));
		if (eq1 && eq2)
			Match m(findUInt(line, 7u),				// poule
				findUInt(line, 5u),					// journee
				eq1,								// equipe 1
				eq2,								// equipe 2
				Equipe::byId(findUInt(line, 3u)),	// arbitre 1
				Equipe::byId(findUInt(line, 4u)),	// arbitre 2
				findUInt(line, 0u));				// id
	}
}

string Match::toCSVLine() const
{
	ostringstream out;
	out << "\"" << (int)m_id << "\",";
	out << "\"" << (int)m_eq1 << "\",";
	out << "\"" << (int)m_eq2 << "\",";
	if (Equipe::byId(m_arb1))
		out << "\"" << (int)m_arb1 << "\",";
	else
		out << "NULL,";
	if (Equipe::byId(m_arb2))
		out << "\"" << (int)m_arb2 << "\",";
	else
		out << "NULL,";
	out << "\"" << (int)m_journee << "\",";
	out << "\"1\",";
	out << "\"" << (int)m_poule << "\",";
	out << "\"" << (int)SAISON << "\",";

	return out.str();
}

unsigned int Match::GetIDMatch()
{
	static const unsigned int MINID = SAISON * 10000u;
	if (Match::m_byId.empty() || Match::m_byId.crbegin()->first < MINID)
		return MINID + 1u;

	return Match::m_byId.crbegin()->first + 1u;
}