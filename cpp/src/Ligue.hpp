#ifndef LIGUE_HPP
#define LIGUE_HPP

#include <Equipe.hpp>
#include <Match.hpp>
#include <vector>

class Ligue
{
public:
	Ligue() = default;
	~Ligue() = default;

	// getters
	const std::vector<Equipe*>& equipes() const { return m_equipe; }

	// setters
	void setIdPoule(const unsigned int id) { m_id = id; }

	// getters
	unsigned int getIdPoule() const { return m_id; }
	unsigned int getIdArbitre() const { return m_id; }

	// adders
	void addEquipe(const std::string& name);
	
	// generation des matchs
	void genereMatchs(const std::vector<std::vector<std::string>>& chapeaux, const bool joueTonChapeau);

private:
	unsigned int m_id = 0u;

	std::vector<Equipe*> m_equipe;

	std::vector<Match> m_match;

	// methodes usuelles
	bool ontIlsDejaJoueCeChapeau(const Equipe* eq1, const Equipe* eq2) const;
};

#endif	// LIGUE_HPP