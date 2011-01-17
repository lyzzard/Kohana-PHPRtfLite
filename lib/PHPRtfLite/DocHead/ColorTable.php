<?php
/*
    PHPRtfLite
    Copyright 2007-2008 Denis Slaveckij <info@phprtf.com>
    Copyright 2010 Steffen Zeidler <sigma_z@web.de>

    This file is part of PHPRtfLite.

    PHPRtfLite is free software: you can redistribute it and/or modify
    it under the terms of the GNU Lesser General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    PHPRtfLite is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU Lesser General Public License for more details.

    You should have received a copy of the GNU Lesser General Public License
    along with PHPRtfLite.  If not, see <http://www.gnu.org/licenses/>.
*/

/**
 * defines color table for the rtf document head
 * @version     1.1.0
 * @author      Steffen Zeidler <sigma_z@web.de>
 * @copyright   2010 Steffen Zeidler
 * @package     PHPRtfLite
 * @subpackage  PHPRtfLite_DocHead
 */

class PHPRtfLite_DocHead_ColorTable
{

    /**
     * defined colors for this color table
     * @var array
     */
    protected $_colors = array('000000');


    private static function getColorAsFullHexCode($color)
    {
        $color = ltrim($color, '#');

        if (strlen($color) == 3) {
            $color = str_repeat(substr($color, 0, 1), 2)
                   . str_repeat(substr($color, 1, 1), 2)
                   . str_repeat(substr($color, 2, 1), 2);
        }

        return strtoupper($color);
    }

    /**
     * Formats color code.
     * @static
     * @param string $color Color
     *
     * @return string rtf color
     * @throws PHPRtfLite_Exception, if color is not a 3or 6 digit hex number
     */
    private static function convertHexColorToRtf($color)
    {
        if (strlen($color) == 6) {
            $red    = hexdec(substr($color, 0, 2));
            $green  = hexdec(substr($color, 2, 2));
            $blue   = hexdec(substr($color, 4, 2));

            return '\red' . $red . '\green' . $green . '\blue' . $blue;
        }

        throw new PHPRtfLite_Exception('Color must be a hex number of length 3 or 6 digits! You gave me: #' . $color);
    }

    /**
     * Adds color to rtf document.
     * @param string $color color
     */
    public function add($color)
    {
        if (!empty($color)) {
            $color = self::getColorAsFullHexCode($color);
            if (!in_array($color, $this->_colors)) {
                $this->_colors[] = $color;
            }
        }
    }

    /**
     * Gets rtf code of color.
     * @param   string $color color
     * @return  string
     */
    public function getColorIndex($color)
    {
        $color = self::getColorAsFullHexCode($color);
        $index = array_search($color, $this->_colors);
        return $index !== false ? $index + 1 : false;
    }

    /**
     * gets rtf color table
     * @return string
     */
    public function getContent()
    {
        $content = '{\colortbl;';

        foreach ($this->_colors as $hexColor) {
            $rtfColor = self::convertHexColorToRtf($hexColor);
            $content .= $rtfColor . ';';
        }
        
        $content .= '}' . "\r\n";

        return $content;
    }

}