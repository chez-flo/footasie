#ifndef EQUIPE_HPP
#define EQUIPE_HPP

#include <string>
#include <map>

class Equipe
{
public:
	Equipe() = default;
	Equipe(const unsigned int id, const std::string& name, const unsigned int terrain = 0u, const std::string& entrainement = "", const std::string& ami = "");
	Equipe(const Equipe& eq);
	~Equipe() = default;

	bool operator==(const Equipe& eq) const;
	bool isValid() const { return m_isValid; }

	static void readCSV(const std::string& filename);
	static Equipe& byName(const std::string& name);

	// getters
	const Equipe& ami() const { return byName(m_ami); }
	const std::string& nom() const { return m_name; }

private:
	unsigned int m_id = 0u;
	std::string m_name = "";
	unsigned int m_terrain = 0u;
	std::string m_entrainement = "";
	std::string m_ami = "";
	bool m_isValid = false;
	
	static std::map<std::string, Equipe> m_byName;
};	//	Equipe

#endif	//	EQUIPE_HPP