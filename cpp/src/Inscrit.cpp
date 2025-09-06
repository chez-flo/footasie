#include <Inscrit.hpp>
#include <iostream>
#include <fstream>
#include <sstream>
#include <algorithm>
#include <Joueur.hpp>

using namespace std;

map<std::string, Inscrit> Inscrit::m_byMail;

namespace {
	bool isFoot(const std::string& line)
	{
		const size_t pos = line.find("s:16:\"\"section-sportive\"\";a:1:{i:0;s:17:\"\"Football Masculin\"\";}");
		return pos != string::npos;
	}

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
			[](unsigned char c) { return std::tolower(c); } // correct
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
				active = (c == ' ') || (c == ' - ');
				return out;
			}
		);
		return s;
	}

	string findNom(const string& line)
	{
		string prenom, nom;

		// prenom
		{
			static const string keyname1 = "s:6:\"\"prenom\"\";";
			static const string keyname2 = "s:9:\"\"telephone\"\";";
			size_t pos1 = line.find(keyname1);
			size_t pos2 = line.find(keyname2);
			prenom = line.substr(pos1 + keyname1.size(), pos2 - pos1 - keyname1.size());

			pos1 = prenom.find("\"\"");
			pos2 = prenom.rfind("\"\"");
			prenom = camelCase(trim(prenom.substr(pos1 + 2u, pos2 - pos1 - 2u)));
		}
		// nom
		{
			static const string keyname1 = "s:9:\"\"your-name\"\";";
			static const string keyname2 = "s:6:\"\"prenom\"\";";
			size_t pos1 = line.find(keyname1);
			size_t pos2 = line.find(keyname2);
			nom = line.substr(pos1 + keyname1.size(), pos2 - pos1 - keyname1.size());

			pos1 = nom.find("\"\"");
			pos2 = nom.rfind("\"\"");
			nom = camelCase(trim(nom.substr(pos1 + 2u, pos2 - pos1 - 2u)));

		}

		return prenom + " " + nom;
	}

	string findMail(const string& line)
	{
		static const string keyname1 = "s:10:\"\"your-email\"\";";
		static const string keyname2 = "s:10:\"\"Entreprise\"\";";
		size_t pos1 = line.find(keyname1);
		size_t pos2 = line.find(keyname2);
		string mail = line.substr(pos1 + keyname1.size(), pos2 - pos1 - keyname1.size());

		pos1 = mail.find("\"\"");
		pos2 = mail.rfind("\"\"");
		mail = trim(mail.substr(pos1 + 2u, pos2 - pos1 - 2u));
		return mail;
	}

	bool isCertifOK(const std::string& line)
	{
		const size_t pos = line.find("s:18:\"\"file-603cfdb7_file\"\";s:0:\"\"\"\";");
		return pos == string::npos;
	}
}


Inscrit::Inscrit(const string& name, const string& mail, const bool hasCertif)
	: m_name(name)
	, m_mail(mail)
	, m_hasCertif(hasCertif)
{
	m_byMail[tolower(m_mail)] = *this;
}


void Inscrit::readCSV(const string& filename)
{
	fstream handle;
	handle.open(filename.c_str(), ios_base::in);

	// lecture premiere ligne
	string line;
	if (!handle.eof() && handle.is_open())
		getline(handle, line);

	// lecture de toutes les lignes
	unsigned int nbLines = 0u;
	unsigned int nbInscrits = 0u;
	unsigned int nbCertifs = 0u;
	while (!handle.eof() && handle.is_open())
	{
		// les 4 1ers champs nous interessent
		getline(handle, line);
		++nbLines;

		const bool ok = isFoot(line);
		if (ok)
		{
			const string name = findNom(line);
			const string mail = findMail(line);
			const bool certif = isCertifOK(line);
			++nbInscrits;
			nbCertifs += (int)certif;
			Inscrit i(name, mail, certif);
			cout << "Inscription de " << name << " - " << mail << ", certif is " << (certif ? "OK" : "NOK") << endl;
		}
	}
	cout << endl;
	cout << "Nb lignes : " << nbLines << endl;
	cout << "Nb inscrits : " << nbInscrits << endl;
	cout << "Nb certifs : " << nbCertifs << endl;
}

Inscrit* Inscrit::byMail(const std::string& mail)
{
	std::map<std::string, Inscrit>::iterator it = m_byMail.find(mail);
	if (it == m_byMail.end())
		return nullptr;

	return &it->second;
}

void Inscrit::toSql(const std::string& filename)
{
	std::fstream handle;
	handle.open(filename.c_str(), std::ios_base::out);

	// UPDATE
	for (map<string, Inscrit>::const_iterator it = m_byMail.begin(); it != m_byMail.end(); it++)
	{
		const Joueur* mail = Joueur::byMail(it->first);
		const Joueur* nom = Joueur::byNom(it->second.m_name);
		if (mail)
			handle << "UPDATE f_joueur SET jou_insc_etat=" 
				<< (it->second.m_hasCertif?2:1) 
				<< " WHERE jou_id=" 
				<< mail->getId() << ";"
				<< endl;
		else if (nom)
			handle << "UPDATE f_joueur SET jou_insc_etat="
				<< (it->second.m_hasCertif ? 2 : 1)
				<< " WHERE jou_nom LIKE \"%"
				<< nom->getNom() << "%\";"
				<< endl;
	}

	// INSERT
	for (map<string, Inscrit>::const_iterator it = m_byMail.begin(); it != m_byMail.end(); it++)
	{
		const Joueur* mail = Joueur::byMail(it->first);
		const Joueur* nom = Joueur::byNom(it->second.m_name);
		if (!mail && !nom)
			handle << "INSERT INTO f_joueur(jou_nom, jou_mail, jou_dro_id, jou_insc_etat) VALUES (\""
				<< it->second.m_name << "\", \""
				<< it->second.m_mail << "\", 3, "
				<< (it->second.m_hasCertif ? 2 : 1) << ");"
				<< endl;
	}
}