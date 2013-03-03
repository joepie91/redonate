for f in *.svg
do
  name=${f%%.*}
  echo "Processing $f..."
  inkscape --export-png=../../public_html/static/images/buttons/$name.png $f
done

