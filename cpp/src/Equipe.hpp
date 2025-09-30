#ifndef EQUIPE_HPP
#define EQUIPE_HPP

#include <string>
#include <vector>
#include <map>

class Equipe
{
public:
	Equipe() = default;
	Equipe(const unsigned int id, const std::string& name, const unsigned int terrain = 0u, const std::vector<std::string>& entrainement = {}, const unsigned int ami = 0u);
	Equipe(const Equipe& eq);
	~Equipe() = default;

	bool operator==(const Equipe& eq) const;
	bool isValid() const { return m_isValid; }

	static void readCSV(const std::string& filename);
	static Equipe* byName(const std::string& name);
	static Equipe* byId(const unsigned int& id) { return byName(m_byId[id]); }
	static std::vector<Equipe*> getAllPlayingEquipe();

	// getters
	unsigned int id() const { return m_id; }
	const Equipe* ami() const { return byName(m_byId[m_ami]); }
	const std::string& nom() const { return m_name; }
	unsigned int terrain() const { return m_terrain; }
	const std::vector<std::string>& entrainement() const { return m_entrainement; }

	unsigned int chapeau() const { return m_chapeau; }
	const unsigned int poule() const { return m_poule; }

	// setters
	void setChapeau(const unsigned int chapeau) { m_chapeau = chapeau; }
	void setPoule(const unsigned int poule) { m_poule = poule; }

private:
	unsigned int m_id = 0u;
	std::string m_name = "";
	unsigned int m_terrain = 0u;
	std::vector<std::string> m_entrainement = {};
	unsigned int m_ami = 0u;
	bool m_isValid = false;

	unsigned int m_chapeau = 0u;
	unsigned int m_poule = 0u;
	
	static std::map<std::string, Equipe> m_byName;
	static std::map<unsigned int, std::string> m_byId;
};	//	Equipe

#endif	//	EQUIPE_HPP