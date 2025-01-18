import { reactive, computed } from 'vue';
const themes = {
  light: {
    background: '#ffffff',
    border: '#07a389',
    text: '#000000',
    primary: '#e6e8ed',
    secondary: '#fc038c',
    accent: '#ccc91f',
    fontFamily: "'Arial', sans-serif",
  },
  dark: {
    background: '#121212',
    border: '#c78b91',
    text: '#ffffff',
    primary: '#c2b2a9',
    secondary: '#a9c2b5',
    accent: '#bca9c2',
    fontFamily: "'Papyrus', fantasy, 'BenSen Handwriting', cursive",
  },
  blue: {
    background: '#e3f2fd',
    border: '#444444',
    text: '#8532a8',
    primary: '#2196f3',
    secondary: '#90caf9',
    accent: '#64b5f6',
    fontFamily: "'Comic Sans MS', cursive, 'Charu Chandan 3D', sans-serif",
  },
  solarized: {
    background: '#fdf6e3',
    border: '#555555',
    text: '#32a834',
    primary: '#268bd2',
    secondary: '#b58900',
    accent: '#2aa198',
    fontFamily: "'Courier New', monospace, 'Charukola Ultra Light', monospace",
  },
} as const;

type ThemeKeys = keyof typeof themes; // 'light' | 'dark' | 'blue' | 'solarized'
type ColorKeys = keyof typeof themes.light; // 'background' | 'border' | 'text' | 'primary' | 'secondary' | 'accent' | 'fontFamily'

// Load the saved theme from localStorage, or use 'light' as the default
const savedTheme = (localStorage.getItem('app-theme') as ThemeKeys) || 'light';

const themeState = reactive({
  current: savedTheme, // Set to the saved theme
});

const useTheme = () => {
  const applyThemeToRoot = () => {
    const currentColors = themes[themeState.current];
    Object.entries(currentColors).forEach(([key, value]) => {
      document.documentElement.style.setProperty(`--${key}`, value);
    });
    // Apply font-family explicitly
    document.documentElement.style.setProperty('--font-family', currentColors.fontFamily);
  };

  const setTheme = (theme: ThemeKeys) => {
    if (themes[theme]) {
      themeState.current = theme;
      localStorage.setItem('app-theme', theme); // Save the selected theme
      applyThemeToRoot();
    } else {
      console.warn(`Theme "${theme}" does not exist.`);
    }
  };

  const getThemeColor = (colorKey: ColorKeys): string => {
    return themes[themeState.current][colorKey];
  };

  const getThemeFontFamily = (): string => {
    return themes[themeState.current].fontFamily;
  };


  const currentTheme = computed(() => themeState.current);

  // Apply the theme on initial load
  applyThemeToRoot();

  return {
    setTheme,
    getThemeColor,
    getThemeFontFamily,
    currentTheme,
  };
};

export default useTheme;
