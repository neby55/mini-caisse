import * as React from 'react';

const ColorModeContext = React.createContext(
  {
    themeMode: '',
    toggleColorMode: () => { }
  }
);

export default ColorModeContext;