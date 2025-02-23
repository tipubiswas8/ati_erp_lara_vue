import { reactive, computed } from 'vue';
const themes = {
  light: {
    background: "#ffffff",
    border: "#e0e0e0",
    text: "#333333",
    primary: "#007bff",
    secondary: "#6c757d",
    accent: "#ffc107",
    fontFamily: "'Arial', sans-serif",
  },
  dark: {
    background: "#121212",
    border: "#444444",
    text: "#ffffff",
    primary: "#bb86fc",
    secondary: "#03dac6",
    accent: "#ff4081",
    fontFamily: "'Papyrus', fantasy, 'BenSen Handwriting', cursive",
  },
  blue: {
    background: "#e3f2fd",
    border: "#90caf9",
    text: "#0d47a1",
    primary: "#1e88e5",
    secondary: "#1565c0",
    accent: "#82b1ff",
    fontFamily: "'Comic Sans MS', cursive, 'Charu Chandan 3D', sans-serif",
  },
  solarized: {
    background: "#fdf6e3",
    border: "#eee8d5",
    text: "#657b83",
    primary: "#268bd2",
    secondary: "#2aa198",
    accent: "#b58900",
    fontFamily: "'Courier New', monospace, 'Charukola Ultra Light', monospace",
  },
  dracula: {
    background: "#282a36",
    border: "#44475a",
    text: "#f8f8f2",
    primary: "#bd93f9",
    secondary: "#50fa7b",
    accent: "#ff79c6",
    fontFamily: "'Fira Code', monospace, 'JetBrains Mono', monospace, 'Courier New', monospace"
  },
  pastel: {
    background: "#f8e8e8",
    border: "#d8bfd8",
    text: "#7d5a5a",
    primary: "#ffb6c1",
    secondary: "#87cefa",
    accent: "#ffdab9",
    fontFamily: "'Poppins', sans-serif, 'Quicksand', sans-serif, 'Dancing Script', cursive"
  }
} as const;

type ThemeKeys = keyof typeof themes; // 'light' | 'dark' | 'blue' | 'solarized' | 'dracula' | 'pastel'
type ColorKeys = keyof typeof themes.light; // 'background' | 'border' | 'text' | 'primary' | 'secondary' | 'accent'

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
