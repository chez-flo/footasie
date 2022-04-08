#ifndef _GETCONFIG_H_
#define _GETCONFIG_H_

#include <string>
#include <vector>
#include <Date.hpp>

std::string getConfigAsString(const std::string &query, const std::string &defaultValue = "NoValue", const std::string &fileName = "config.ini");
int getConfigAsInt(const std::string &query, const int &defaultValue = 0, const std::string &fileName = "config.ini");
unsigned int getConfigAsUInt(const std::string &query, const unsigned int &defaultValue = 0u, const std::string &fileName = "config.ini");
double getConfigAsDouble(const std::string &query, const double &defaultValue = 0., const std::string &fileName = "config.ini");
Date getConfigAsDate(const std::string& query, const Date& defaultValue = { 0 }, const std::string& fileName = "config.ini");

std::vector<std::string> getConfigAsVectorString(const std::string &query, const std::vector<std::string> &defaultValue = std::vector<std::string>(), const std::string &fileName = "config.ini");
std::vector<int> getConfigAsVectorInt(const std::string &query, const std::vector<int> &defaultValue = std::vector<int>(), const std::string &fileName = "config.ini");
std::vector<unsigned int> getConfigAsVectorUInt(const std::string &query, const std::vector<unsigned int> &defaultValue = std::vector<unsigned int>(), const std::string &fileName = "config.ini");
std::vector<float> getConfigAsVectorFloat(const std::string &query, const std::vector<float> &defaultValue = std::vector<float>(), const std::string &fileName = "config.ini");
std::vector<double> getConfigAsVectorDouble(const std::string &query, const std::vector<double> &defaultValue = std::vector<double>(), const std::string &fileName = "config.ini");
std::vector<Date> getConfigAsVectorDate(const std::string& query, const std::vector<Date>& defaultValue = std::vector<Date>(), const std::string& fileName = "config.ini");

void setConfigString(const std::string &query, const std::string &value, const std::string &fileName = "config.ini");
void setConfigInt(const std::string &query, const int &value, const std::string &fileName = "config.ini");
void setConfigUInt(const std::string &query, const unsigned int &value, const std::string &fileName = "config.ini");
void setConfigDouble(const std::string &query, const double &value, const std::string &fileName = "config.ini");
void setConfigDate(const std::string& query, const Date& value, const std::string& fileName = "config.ini");

void setConfigVectorString(const std::string &query, const std::vector<std::string> &value, const std::string &fileName = "config.ini");
void setConfigVectorInt(const std::string &query, const std::vector<int> &value, const std::string &fileName = "config.ini");
void setConfigVectorUInt(const std::string &query, const std::vector<unsigned int> &value, const std::string &fileName = "config.ini");
void setConfigVectorFloat(const std::string &query, const std::vector<float> &value, const std::string &fileName = "config.ini");
void setConfigVectorDouble(const std::string &query, const std::vector<double> &value, const std::string &fileName = "config.ini");
void setConfigVectorDate(const std::string& query, const std::vector<Date>& value, const std::string& fileName = "config.ini");


#endif  //      _GETCONFIG_H_