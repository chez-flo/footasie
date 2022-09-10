#ifndef GLOBAL_HPP
#define GLOBAL_HPP

#include <map>
#include <string>

static const unsigned int SAISON = 2022u;
static const std::map<std::string, unsigned int> POULES = {
	{"A",  11u},
	{"B1", 12u},
	{"B2", 13u},
	{"C1", 14u},
	{"C2", 15u},
	{"D",  19u},
	{"C",  21u}
};

#endif