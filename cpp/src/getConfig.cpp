#include "getConfig.hpp"
#include <stdlib.h>
#include <fstream>
#include <vector>
#include <errno.h>
#include <mutex>

using namespace std;

mutex MUTEX;

#define LOCK MUTEX.lock()
#define UNLOCK MUTEX.unlock()

string getConfigAsString(const string &query, const string &defaultValue, const string &fileName)
{
    LOCK;
    fstream handle;
    handle.open(fileName.c_str(), ios_base::in);
    while(!handle.eof() && handle.is_open())
    {
        string line;
        getline(handle, line);
        string::size_type pos = line.find("=");
        if (pos == string::npos)
        {
            continue;
        }
        if (pos >= line.length())
        {
            continue;
        }
        if (line.compare(0,pos, query)!=0)
        {
            continue;
        }
        handle.close();
        UNLOCK;
        // suppression du \r de fin si windows
        if (*line.rbegin() == '\r')    line = line.substr(0, line.size() - 1u);
        
        return line.substr(pos+1);
    }
    handle.close();
    UNLOCK;
    
    setConfigString(query, defaultValue, fileName);
    return defaultValue;
}

int getConfigAsInt(const string &query, const int &defaultValue, const string &fileName)
{
    char buffer[64];
    snprintf(buffer, sizeof(buffer), "%d", defaultValue);
    int res = strtol(getConfigAsString(query, buffer, fileName).c_str(), NULL, 10);
    if (errno)
    {
        res = defaultValue;
    }
    return res;
}

unsigned int getConfigAsUInt(const string &query, const unsigned int &defaultValue, const string &fileName)
{
    char buffer[64];
    snprintf(buffer, sizeof(buffer), "%u", defaultValue);
    unsigned int res = strtoul(getConfigAsString(query, buffer, fileName).c_str(), NULL, 10);
    if (errno)
    {
        res = defaultValue;
    }
    return res;
}

double getConfigAsDouble(const string &query, const double &defaultValue, const string &fileName)
{
    char buffer[64];
    snprintf(buffer, sizeof(buffer), "%lf", defaultValue);
    double res = strtod(getConfigAsString(query, buffer, fileName).c_str(), NULL);
    if (errno)
    {
        res = defaultValue;
    }
    return res;
}

Date getConfigAsDate(const std::string& query, const Date& defaultValue, const std::string& fileName)
{
    Date res(getConfigAsString(query, defaultValue.toStr(), fileName));
    return res;
}

vector< string > getConfigAsVectorString(const string& query, const vector< string >& defaultValue, const string& fileName)
{
    LOCK;
    fstream handle;
    handle.open(fileName.c_str(), ios_base::in);
    while(!handle.eof() && handle.is_open())
    {
        string line;
        getline(handle, line);
        string::size_type pos = line.find("=");
        if (pos == string::npos)
        {
            continue;
        }
        if (pos >= line.length())
        {
            continue;
        }
        if (line.compare(0,pos, query)!=0)
        {
            continue;
        }
        handle.close();
        UNLOCK;
        // suppression du \r de fin si windows
        if (*line.rbegin() == '\r')    line = line.substr(0, line.size() - 1u);
        
        string values;
        if (pos < line.length()-1)
            values = line.substr(pos+1);
        values += ";";
        
        vector<string> out;
        pos = values.find(";");
        while (pos < values.length() && pos != string::npos)
        {
            out.push_back(values.substr(0, pos));
            values = values.substr(pos+1);
            pos = values.find(";");
        }
        
        return out;
    }
    handle.close();
    UNLOCK;
    
    setConfigVectorString(query, defaultValue, fileName);
    return defaultValue;
}

vector< int > getConfigAsVectorInt(const string& query, const vector< int >& defaultValue, const string& fileName)
{
    vector<string> values;
    for (vector<int>::const_iterator it=defaultValue.begin(); it!=defaultValue.end(); it++)
    {
        char buffer[64];
        snprintf(buffer, sizeof(buffer), "%d", *it);
        values.push_back(buffer);
    }
    
    values = getConfigAsVectorString(query, values, fileName);
    
    vector<int> res;
    for (vector<string>::const_iterator it=values.begin(); it!=values.end(); it++)
    {
        if (!it->empty())
        {
            int val = 0;
            if (sscanf_s(it->c_str(), "%d", &val) != 1)
            {
                res = defaultValue;
                return res;
            }

            res.push_back(val);
        }
    }
    
    return res;
}

std::vector< unsigned int > getConfigAsVectorUInt(const string& query, const std::vector< unsigned int >& defaultValue, const string& fileName)
{
    vector<string> values;
    for (vector<unsigned int>::const_iterator it=defaultValue.begin(); it!=defaultValue.end(); it++)
    {
        char buffer[64];
        snprintf(buffer, sizeof(buffer), "%u", *it);
        values.push_back(buffer);
    }
    
    values = getConfigAsVectorString(query, values, fileName);
    
    vector<unsigned int> res;
    for (vector<string>::const_iterator it=values.begin(); it!=values.end(); it++)
    {
        if (!it->empty())
        {
            unsigned int val = 0u;
            if (sscanf_s(it->c_str(), "%u", &val) != 1)
            {
                res = defaultValue;
                return res;
            }

            res.push_back(val);
        }
    }
    
    return res;
}

vector< float > getConfigAsVectorFloat(const string& query, const vector< float >& defaultValue, const string& fileName)
{
    vector<string> values;
    for (vector<float>::const_iterator it=defaultValue.begin(); it!=defaultValue.end(); it++)
    {
        char buffer[64];
        snprintf(buffer, sizeof(buffer), "%f", *it);
        values.push_back(buffer);
    }
    
    values = getConfigAsVectorString(query, values, fileName);
    
    vector<float> res;
    for (vector<string>::const_iterator it=values.begin(); it!=values.end(); it++)
    {
        if (!it->empty())
        {
            float val = 0.f;
            if (sscanf_s(it->c_str(), "%f", &val) != 1)
            {
                res = defaultValue;
                return res;
            }

            res.push_back(val);
        }
    }
    
    return res;
}

vector< double > getConfigAsVectorDouble(const string& query, const vector< double >& defaultValue, const string& fileName)
{
    vector<string> values;
    for (vector<double>::const_iterator it=defaultValue.begin(); it!=defaultValue.end(); it++)
    {
        char buffer[64];
        snprintf(buffer, sizeof(buffer), "%lf", *it);
        values.push_back(buffer);
    }
    
    values = getConfigAsVectorString(query, values, fileName);
    
    vector<double> res;
    for (vector<string>::const_iterator it=values.begin(); it!=values.end(); it++)
    {
        if (!it->empty())
        {
            double val = 0.;
            if (sscanf_s(it->c_str(), "%lf", &val) != 1)
            {
                res = defaultValue;
                return res;
            }

            res.push_back(val);
        }
    }
    
    return res;
}

vector<Date> getConfigAsVectorDate(const std::string& query, const std::vector<Date>& defaultValue, const std::string& fileName)
{
    vector<string> values;
    for (vector<Date>::const_iterator it = defaultValue.begin(); it != defaultValue.end(); it++)
        values.push_back(it->toStr());

    values = getConfigAsVectorString(query, values, fileName);

    vector<Date> res;
    for (vector<string>::const_iterator it = values.begin(); it != values.end(); it++)
    {
        Date date(*it);
        if (date.isValid())
            res.push_back(date);
    }

    return res;
}

void setConfigString(const string &query, const string &value, const string &fileName)
{
    LOCK;
    fstream handle;
    //  ouverture et stockage de toutes les lignes
    vector<string> lines;
    handle.open(fileName.c_str(), ios_base::in);
    while(!handle.eof() && handle.is_open())
    {
        string line;
        getline(handle, line);
        lines.push_back(line);
    }
    handle.close();
    
    //  construction de la ligne a ajouter
    string lineToAdd = query + "=" + value;
    
    //  analyse des lignes, modification ou ajout de la ligne
    vector<string>::iterator it=lines.begin();
    for (; it!=lines.end(); it++)
    {
        string::size_type pos =it->find("=");
        if (pos == string::npos)
        {
            continue;
        }
        if (pos >= it->length()-1)
        {
            continue;
        }
        if (it->compare(0,pos, query)==0)
        {
            break;
        }
    }
    if (it==lines.end())
    {
        lines.push_back(lineToAdd);
    }
    else
    {
        *it = lineToAdd;
    }
    
    //  reecriture fichier
    fstream handle2;    //  2eme handle car eofbit leve aussi failbit
    handle2.open(fileName.c_str(), ios_base::out | ios_base::trunc);
    for (unsigned int i=0; i<lines.size()-1; ++i)
    {
        handle2 << lines[i] << endl;
    }
    handle2 << lines.back();    //  pas de endl en fin de fichier
    handle2.close();
    UNLOCK;
}

void setConfigInt(const string &query, const int &value, const string &fileName)
{
    char buffer[64];
    snprintf(buffer, sizeof(buffer), "%d", value);
    setConfigString(query, buffer, fileName);
}

void setConfigUInt(const string &query, const unsigned int &value, const string &fileName)
{
    char buffer[64];
    snprintf(buffer, sizeof(buffer), "%u", value);
    setConfigString(query, buffer, fileName);
}

void setConfigDouble(const string &query, const double &value, const string &fileName)
{
    char buffer[64];
    snprintf(buffer, sizeof(buffer), "%lf", value);
    setConfigString(query, buffer, fileName);
}

void setConfigDate(const std::string& query, const Date& value, const std::string& fileName)
{
    setConfigString(query, value.toStr(), fileName);
}

void setConfigVectorString(const string& query, const vector< string >& value, const string& fileName)
{
    string values;
    for (vector<string>::const_iterator it=value.begin(); it!=value.end(); it++)
    {
        if (it!=value.begin())  values += ";";
        values += *it;
    }
    setConfigString(query, values, fileName);
}

void setConfigVectorInt(const string& query, const vector< int >& value, const string& fileName)
{
    vector<string> values;
    for (vector<int>::const_iterator it=value.begin(); it!=value.end(); it++)
    {
        char buffer[64];
        snprintf(buffer, sizeof(buffer), "%d", *it);
        values.push_back(buffer);
    }
    setConfigVectorString(query, values, fileName);
}

void setConfigVectorUInt(const string& query, const std::vector< unsigned int >& value, const string& fileName)
{
    vector<string> values;
    for (vector<unsigned int>::const_iterator it=value.begin(); it!=value.end(); it++)
    {
        char buffer[64];
        snprintf(buffer, sizeof(buffer), "%u", *it);
        values.push_back(buffer);
    }
    setConfigVectorString(query, values, fileName);
}

void setConfigVectorFloat(const string& query, const vector< float >& value, const string& fileName)
{
    vector<string> values;
    for (vector<float>::const_iterator it=value.begin(); it!=value.end(); it++)
    {
        char buffer[64];
        snprintf(buffer, sizeof(buffer), "%f", *it);
        values.push_back(buffer);
    }
    setConfigVectorString(query, values, fileName);
}

void setConfigVectorDouble(const string& query, const vector< double >& value, const string& fileName)
{
    vector<string> values;
    for (vector<double>::const_iterator it=value.begin(); it!=value.end(); it++)
    {
        char buffer[64];
        snprintf(buffer, sizeof(buffer), "%lf", *it);
        values.push_back(buffer);
    }
    setConfigVectorString(query, values, fileName);
}

void setConfigVectorDate(const std::string& query, const std::vector<Date>& value, const std::string& fileName)
{
    vector<string> values;
    for (vector<Date>::const_iterator it = value.begin(); it != value.end(); it++)
        values.push_back(it->toStr());

    setConfigVectorString(query, values, fileName);
}