#ifndef POULE_HPP
#define POULE_HPP

#include <Equipe.hpp>
#include <Match.hpp>
#include <vector>

class Poule
{
public:
	Poule() = default;
	~Poule() = default;

	// getters
	const std::vector<Equipe>& equipes() const { return m_equipe; }

	// setters
	void setIdPoule(const unsigned int id) { m_id = id; }

	// adders
	void addEquipe(const std::string& name);
	void addArbitre(const std::string& name);
	
	// generation des matchs
	void genereMatchs();

private:
	unsigned int m_id = 0u;

	std::vector<Equipe> m_equipe;
	std::vector<Equipe> m_arbitre;

	std::vector<Match> m_match;

	// methodes usuelles
	bool ontIlsDejaJoue(const Equipe* eq1, const Equipe* eq2) const;
};

#endif	// POULE_HPP