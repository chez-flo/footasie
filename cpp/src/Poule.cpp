#include <Poule.hpp>

void Poule::addEquipe(const std::string& name)
{
	const Equipe& equipe = Equipe::byName(name);
	if (equipe.isValid())
		m_equipe.push_back(equipe);
}

void Poule::addArbitre(const std::string& name)
{
	const Equipe& equipe = Equipe::byName(name);
	if (equipe.isValid())
		m_arbitre.push_back(equipe);
}