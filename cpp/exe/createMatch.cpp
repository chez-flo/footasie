#include <iostream>
#include <getConfig.hpp>

#include <Equipe.hpp>

int main(int argc, char **argv)
{
    Equipe::readCSV("data/f_equipe.csv");
    return 0;
}