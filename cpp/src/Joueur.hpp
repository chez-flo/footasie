#ifndef JOUEUR_HPP
#define JOUEUR_HPP

#include <string>
#include <vector>
#include <map>

class Joueur
{
public:
	Joueur() = default;
	Joueur(const unsigned int id, const std::string& name, const std::string& mail, const int insc_state);
	~Joueur() = default;

	unsigned int getId() const { return m_id; }
	const std::string& getNom() const { return m_name; }

	static void readCSV(const std::string& filename);
	static Joueur* byMail(const std::string& mail);
	static Joueur* byNom(const std::string& nom);

private:
	unsigned int m_id = 0u;
	std::string m_name = "";
	std::string m_mail = "";
	int m_insc_state = 0;

	static std::map<std::string, Joueur> m_byMail;
	static std::map<std::string, Joueur> m_byNom;
};	// Joueur

#endif	//	JOUEUR_HPP