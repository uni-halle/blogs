PLUGIN_FILES = $(shell git ls-files)

VERSION = $(shell grep 'Stable tag' readme.txt | cut -d' ' -f 3)
TARGET = social-polls-by-opinionstage-$(VERSION).zip

$(TARGET): $(PLUGIN_FILES)
	zip $(TARGET) $(PLUGIN_FILES)

clean:
	-$(RM) -r assets/*/node_modules
	-$(RM) *.zip

.PHONY: clean
