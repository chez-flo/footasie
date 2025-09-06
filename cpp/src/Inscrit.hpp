#ifndef INSCRIT_HPP
#define INSCRIT_HPP

#include <string>
#include <vector>
#include <map>

class Inscrit
{
public:
	Inscrit() = default;
	Inscrit(const std::string& name, const std::string &mail, const bool hasCertif);
	~Inscrit() = default;

	static void readCSV(const std::string& filename);
	static Inscrit* byMail(const std::string& mail);
	static void toSql(const std::string& filename);

private:
	std::string m_name = "";
	std::string m_mail = "";
	bool m_hasCertif = false;

	static std::map<std::string, Inscrit> m_byMail;
};	// Inscrit

#endif	//	INSCRIT_HPP