import { reactive, computed } from 'vue'

const themes = {
  light: {
    background: '#ffffff',
    border: '#07a389',
    text: '#000000',
    primary: '#03fc77',
    secondary: '#fc038c',
    accent: '#ccc91f',
    fontFamily: "'Arial', sans-serif", // Add font-family
  },
  dark: {
    background: '#121212',
    border: '#c78b91',
    text: '#ffffff',
    primary: '#c2b2a9',
    secondary: '#a9c2b5',
    accent: '#bca9c2',
    fontFamily: "'Roboto', sans-serif", // Add font-family
  },
  blue: {
    background: '#e3f2fd',
    border: '#444444',
    text: '#8532a8',
    primary: '#2196f3',
    secondary: '#90caf9',
    accent: '#64b5f6',
    fontFamily: "'Tahoma', sans-serif", // Add font-family
  },
  solarized: {
    background: '#fdf6e3',
    border: '#555555',
    text: '#32a834',
    primary: '#268bd2',
    secondary: '#b58900',
    accent: '#2aa198',
    fontFamily: "'Courier New', monospace", // Add font-family
  },
} as const // Use `as const` to infer literal types

type ThemeKeys = keyof typeof themes // 'light' | 'dark' | 'blue' | 'solarized'
type ColorKeys = keyof typeof themes.light // 'background' | 'border' | 'text' | 'primary' | 'secondary' | 'accent' | 'fontFamily'

const themeState = reactive({
  current: 'light' as ThemeKeys, // Default theme with type
})

const useTheme = () => {
  const applyThemeToRoot = () => {
    const currentColors = themes[themeState.current]
    Object.entries(currentColors).forEach(([key, value]) => {
      document.documentElement.style.setProperty(`--${key}`, value)
    })
    // Apply font-family explicitly
    document.documentElement.style.setProperty('font-family', currentColors.fontFamily)
  }

  const setTheme = (theme: ThemeKeys) => {
    if (themes[theme]) {
      themeState.current = theme
      applyThemeToRoot()
    } else {
      console.warn(`Theme "${theme}" does not exist.`)
    }
  }

  const getThemeColor = (colorKey: ColorKeys): string => {
    return themes[themeState.current][colorKey]
  }

  const currentTheme = computed(() => themeState.current)

  return {
    setTheme,
    getThemeColor,
    currentTheme,
  }
}

export default useTheme
