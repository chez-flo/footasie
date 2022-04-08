#include <iostream>
#include <getConfig.hpp>
#include <Date.hpp>

int main(int argc, char **argv)
{
    Date date = getConfigAsDate("ma date", Date("01/04/2022"), "config.ini");
    std::cout << "Date renseignee: " << date << std::endl;

    date -= 31;
    std::cout << "Date renseignee -1mois: " << date << std::endl;

    return 0;
}