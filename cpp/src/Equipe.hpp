#ifndef EQUIPE_HPP
#define EQUIPE_HPP

#include <string>
#include <map>

class Equipe
{
public:
	Equipe() = default;
	Equipe(const unsigned int id, const std::string& name, const unsigned int terrain = 0u, const std::string& entrainement = "", const unsigned int ami = 0u);
	Equipe(const Equipe& eq);
	~Equipe() = default;

	bool operator==(const Equipe& eq) const;
	bool isValid() const { return m_isValid; }

	static void readCSV(const std::string& filename);
	static Equipe* byName(const std::string& name);
	static Equipe* byId(const unsigned int& id) { return byName(m_byId[id]); }

	// getters
	unsigned int id() const { return m_id; }
	const Equipe* ami() const { return byName(m_byId[m_ami]); }
	const std::string& nom() const { return m_name; }
	unsigned int terrain() const { return m_terrain; }
	const std::string& entrainement() const { return m_entrainement; }

private:
	unsigned int m_id = 0u;
	std::string m_name = "";
	unsigned int m_terrain = 0u;
	std::string m_entrainement = "";
	unsigned int m_ami = 0u;
	bool m_isValid = false;
	
	static std::map<std::string, Equipe> m_byName;
	static std::map<unsigned int, std::string> m_byId;
};	//	Equipe

#endif	//	EQUIPE_HPP