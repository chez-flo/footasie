project(foot)
cmake_minimum_required(VERSION 3.1)

include_directories(src)
set(HEADERS
	src/Date.hpp
	src/getConfig.hpp)

set(SOURCES
	src/Date.cpp	
	src/getConfig.cpp)



add_executable(createCreneau exe/createCreneau.cpp ${HEADERS} ${SOURCES})