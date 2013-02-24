for f in *.svg
do
  name=${f%%.*}
  echo "Processing $f..."
  inkscape --export-png=../../public_html/static/images/icons/$name.png $f
done

