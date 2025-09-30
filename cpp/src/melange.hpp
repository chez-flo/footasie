#ifndef MELANGE_HPP
#define MELANGE_HPP

#include <vector>
#include <random>
#include <algorithm>

template<typename T>
std::vector<T> melange(const std::vector<T>& in)
{
	static std::random_device rd;
	static std::default_random_engine gen(rd());
	std::vector<T> cpy = in;
	std::shuffle(std::begin(cpy), std::end(cpy), gen);
	return cpy;
}

#endif	//	MELANGE_HPP