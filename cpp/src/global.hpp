#ifndef GLOBAL_HPP
#define GLOBAL_HPP

#include <map>
#include <string>

static const unsigned int SAISON = 2023u;
static const std::map<std::string, unsigned int> POULES = {
	{"A",  11u},
	{"B1", 12u},
	{"B2", 13u},
	{"C1", 14u},
	{"C2", 15u},
	{"D1", 16u},
	{"D2", 17u},
	{"D3", 18u},
	{"D",  19u},
	{"C3", 20u},
	{"C",  21u}
};
static const std::map<std::string, unsigned int> POULESCOUPE = {
	{"A", 31u},
	{"B", 32u},
	{"C", 33u},
	{"D", 34u},
	{"E", 35u},
	{"F", 36u},
	{"G", 37u},
	{"H", 38u},
	{"I", 39u},
	{"J", 40u},
	{"K", 41u},
	{"L", 42u},
	{"M", 43u},
	{"N", 44u},
	{"O", 45u},
	{"P", 46u},
	{"1/16",	55u},
	{"1/8",		56u},
	{"1/4",		57u},
	{"1/2",		58u},
	{"Finale",	59u},
};

#endif