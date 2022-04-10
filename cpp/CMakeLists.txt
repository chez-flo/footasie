project(foot)
cmake_minimum_required(VERSION 3.1)

include_directories(src)
set(HEADERS
	src/Creneau.hpp
	src/Date.hpp
	src/getConfig.hpp
	src/Match.hpp)

set(SOURCES
	src/Creneau.cpp
	src/Date.cpp	
	src/getConfig.cpp
	src/Match.cpp)



add_executable(createCreneau exe/createCreneau.cpp ${HEADERS} ${SOURCES})