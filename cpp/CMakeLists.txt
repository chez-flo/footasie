project(foot)
cmake_minimum_required(VERSION 3.1)

#SOURCES
include_directories(src)
set(HEADERS
	src/Creneau.hpp
	src/Date.hpp
	src/Equipe.hpp
	src/getConfig.hpp
	src/global.hpp
	src/Ligue.hpp
	src/Match.hpp
	src/Poule.hpp
	src/Inscrit.hpp
	src/Joueur.hpp)

set(SOURCES
	src/Creneau.cpp
	src/Date.cpp
	src/Equipe.cpp
	src/getConfig.cpp
	src/Ligue.cpp
	src/Match.cpp
	src/Poule.cpp
	src/Inscrit.cpp
	src/Joueur.cpp)

#DATA
set(DATA ${CMAKE_CURRENT_SOURCE_DIR}/data)

#EXE
add_executable(createCreneau exe/createCreneau.cpp ${HEADERS} ${SOURCES})
add_executable(createMatch exe/createMatch.cpp ${HEADERS} ${SOURCES})
add_executable(createChampionnat exe/createChampionnat.cpp ${HEADERS} ${SOURCES})
add_executable(createMatchCoupe exe/createMatchCoupe.cpp ${HEADERS} ${SOURCES})
add_executable(createMatchLigue exe/createMatchLigue.cpp ${HEADERS} ${SOURCES})
add_executable(createCoupe exe/createCoupe.cpp ${HEADERS} ${SOURCES})
add_executable(rondeSuisse exe/rondeSuisse.cpp ${HEADERS} ${SOURCES})
add_executable(updateInscrits exe/updateInscrits.cpp ${HEADERS} ${SOURCES})

#LINK TO DATA
# pour que cela fonctionne sur Windows 10/+, activer le mode developpeur de Windows
# TODO : tester sur Linux (effets de bord possibles)
add_custom_command(	TARGET createMatch POST_BUILD
					COMMAND ${CMAKE_COMMAND} -E create_symlink ${DATA}/config.ini ${CMAKE_CURRENT_BINARY_DIR}/config.ini
					COMMAND ${CMAKE_COMMAND} -E create_symlink ${DATA} ${CMAKE_CURRENT_BINARY_DIR}/data) 
