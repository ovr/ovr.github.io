#include <cstdlib>
#include <iostream>

#define MY_TEXT 1

#define SIMPLE_MACROS(a, b) \
	cout << a << a << endl; \
	cout << b << b << endl; \
	\

using namespace std;

int main()
{
	cout << MY_TEXT << endl;
	
	SIMPLE_MACROS("1", "b");

	return 1;
}
