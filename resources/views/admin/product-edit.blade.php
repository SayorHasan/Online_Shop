@extends('layouts.admin')
@section('content')

    <!-- main-content-wrap -->
    <div class="main-content-inner">
        <!-- main-content-wrap -->
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Edit Product</h3>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li>
                        <a href="{{ route('admin.index')}}">
                            <div class="text-tiny">Dashboard</div></a>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <a href="{{ route('admin.products')}}">
                            <div class="text-tiny">Products</div></a>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <div class="text-tiny">Edit Product</div>
                    </li>
                </ul>
            </div>
            <!-- form-edit-product -->
            <form class="tf-section-2 form-edit-product" method="POST" enctype="multipart/form-data" action="{{route('admin.product.update')}}" >
                @csrf
                @method('PUT')
                <input type="hidden" name="id" value="{{ $product->id }}">
                <input type="hidden" name="remove_main_image" id="remove_main_image" value="0">
                <div class="wg-box">
                    <fieldset class="name">
                        <div class="body-title mb-10">Product name <span class="tf-color-1">*</span></div>
                        <input class="mb-10" type="text" placeholder="Enter product name" name="name" tabindex="0" value="{{ old('name', $product->name) }}" aria-required="true">
                        <div class="text-tiny">Do not exceed 100 characters when entering the product name.</div>
                    </fieldset>
                    @error('name') <span class="alert alert-danger text-center">{{$message}}</span> @enderror
                    <fieldset class="name">
                        <div class="body-title mb-10">Slug <span class="tf-color-1">*</span></div>
                        <input class="mb-10" type="text" placeholder="Enter product slug" name="slug" tabindex="0" value="{{ old('slug', $product->slug) }}" aria-required="true">
                        <div class="text-tiny">Do not exceed 100 characters when entering the product name.</div>
                    </fieldset>
                    @error('slug') <span class="alert alert-danger text-center">{{$message}}</span> @enderror
                    <div class="gap22 cols">
                        <fieldset class="category">
                            <div class="body-title mb-10">Category <span class="tf-color-1">*</span></div>
                            <div class="select">
                                <select class="" name="category_id">
                                    <option value="">Choose category</option>
                                    @foreach ($categories as $category)
                                    <option value="{{$category->id}}" {{ $product->category_id == $category->id ? 'selected' : '' }}>{{$category->name}}</option>
                                    @endforeach                                                                 
                                </select>
                            </div>
                        </fieldset>
                        @error('category_id') <span class="alert alert-danger text-center">{{$message}}</span> @enderror
                        <fieldset class="brand">
                            <div class="body-title mb-10">Brand <span class="tf-color-1">*</span></div>
                            <div class="select">
                                <select class="" name="brand_id">
                                    <option value="">Choose Brand</option>
                                    @foreach ($brands as $brand)
                                    <option value="{{$brand->id}}" {{ $product->brand_id == $brand->id ? 'selected' : '' }}>{{$brand->name}}</option>
                                    @endforeach                                      
                                </select>
                            </div>
                        </fieldset>
                        @error('brand_id') <span class="alert alert-danger text-center">{{$message}}</span> @enderror
                    </div>
                    <fieldset class="shortdescription">
                        <div class="body-title mb-10">Short Description <span class="tf-color-1">*</span></div>
                        <textarea class="mb-10 ht-150" name="short_description" placeholder="Short Description" tabindex="0" aria-required="true">{{ old('short_description', $product->short_description) }}</textarea>
                        <div class="text-tiny">Do not exceed 100 characters when entering the product name.</div>
                    </fieldset>
                    @error('short_description') <span class="alert alert-danger text-center">{{$message}}</span> @enderror
                    
                    <fieldset class="description">
                        <div class="body-title mb-10">Description <span class="tf-color-1">*</span></div>
                        <textarea class="mb-10" name="description" placeholder="Description" tabindex="0" aria-required="true">{{ old('description', $product->description) }}</textarea>
                        <div class="text-tiny">Do not exceed 100 characters when entering the product name.</div>
                    </fieldset>
                    @error('description') <span class="alert alert-danger text-center">{{$message}}</span> @enderror
                </div>
                <div class="wg-box">
                    <fieldset>
                        <div class="body-title">Upload images <span class="tf-color-1">*</span></div>
                        <div class="upload-image flex-grow">
                            <div class="item" id="imgpreview" style="{{ $product->image ? 'display:block' : 'display:none' }}">                            
                                <img src="{{ $product->image ? asset('uploads/products/thumbnails/'.$product->image) : asset('images/upload/upload-1.png') }}" class="effect8" alt="">
                                <button type="button" class="remove-main-image" onclick="removeMainImage()" style="position: absolute; top: -5px; right: -5px; background: red; color: white; border: none; border-radius: 50%; width: 20px; height: 20px; cursor: pointer; font-size: 14px;">×</button>
                            </div>
                            <div id="upload-file" class="item up-load" style="{{ $product->image ? 'display:none' : 'display:block' }}">
                                <label class="uploadfile" for="myFile">
                                    <span class="icon">
                                        <i class="icon-upload-cloud"></i>
                                    </span>
                                    <span class="body-text">Drop your images here or select <span class="tf-color">click to browse</span></span>
                                    <input type="file" id="myFile" name="image" accept="image/*">
                                </label>
                            </div>
                        </div>
                        <div class="text-tiny">Upload a product image. File size must be less than 2MB and in JPG, PNG or JPEG format.</div>
                        @error('image') <span class="alert alert-danger text-center">{{$message}}</span> @enderror
                    </fieldset>
                    <fieldset>
                        <div class="body-title">Upload gallery images</div>
                        <div class="upload-image flex-grow">
                            <div class="item" id="galUpload">
                                @if($product->images)
                                    @php
                                        $galleryImages = explode(',', $product->images);
                                    @endphp
                                    @foreach($galleryImages as $gimage)
                                        @if($gimage)
                                            <div class="item gitems">
                                                <img src="{{ asset('uploads/products/thumbnails/'.trim($gimage)) }}" />
                                                <button type="button" class="remove-gallery-item" onclick="removeGalleryItem(this)" style="position: absolute; top: -5px; right: -5px; background: red; color: white; border: none; border-radius: 50%; width: 20px; height: 20px; cursor: pointer;">×</button>
                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                            <div class="item up-load">
                                <label class="uploadfile" for="gFile">
                                    <span class="icon">
                                        <i class="icon-upload-cloud"></i>
                                    </span>
                                    <span class="body-text">Drop your images here or select <span class="tf-color">click to browse</span></span>
                                    <input type="file" id="gFile" name="images[]" accept="image/*" multiple>
                                </label>
                            </div>
                        </div>
                        <div class="text-tiny">Upload gallery images. File size must be less than 2MB and in JPG, PNG or JPEG format.</div>
                        @error('images.*') <span class="alert alert-danger text-center">{{$message}}</span> @enderror
                    </fieldset>
                </div>
                <div class="wg-box">
                    <div class="gap22 cols">
                        <fieldset class="regularprice">
                            <div class="body-title mb-10">Regular Price <span class="tf-color-1">*</span></div>
                            <input class="mb-10" type="number" placeholder="Enter regular price" name="regular_price" tabindex="0" value="{{ old('regular_price', $product->regular_price) }}" aria-required="true" step="0.01" min="0">
                        </fieldset>
                        @error('regular_price') <span class="alert alert-danger text-center">{{$message}}</span> @enderror
                        <fieldset class="saleprice">
                            <div class="body-title mb-10">Sale Price <span class="tf-color-1">*</span></div>
                            <input class="mb-10" type="number" placeholder="Enter sale price" name="sale_price" tabindex="0" value="{{ old('sale_price', $product->sale_price) }}" aria-required="true" step="0.01" min="0">
                        </fieldset>
                        @error('sale_price') <span class="alert alert-danger text-center">{{$message}}</span> @enderror
                    </div>
                    <div class="gap22 cols">
                        <fieldset class="sku">
                            <div class="body-title mb-10">SKU <span class="tf-color-1">*</span></div>
                            <input class="mb-10" type="text" placeholder="Enter SKU" name="SKU" tabindex="0" value="{{ old('SKU', $product->SKU) }}" aria-required="true">
                        </fieldset>
                        @error('SKU') <span class="alert alert-danger text-center">{{$message}}</span> @enderror
                        <fieldset class="stockstatus">
                            <div class="body-title mb-10">Stock Status <span class="tf-color-1">*</span></div>
                            <div class="select">
                                <select class="" name="stock_status">
                                    <option value="in_stock" {{ $product->stock_status == 'in_stock' ? 'selected' : '' }}>In Stock</option>
                                    <option value="out_of_stock" {{ $product->stock_status == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                                </select>
                            </div>
                        </fieldset>
                        @error('stock_status') <span class="alert alert-danger text-center">{{$message}}</span> @enderror
                    </div>
                    <div class="gap22 cols">
                        <fieldset class="featured">
                            <div class="body-title mb-10">Featured <span class="tf-color-1">*</span></div>
                            <div class="select">
                                <select class="" name="featured">
                                    <option value="0" {{ $product->featured == 0 ? 'selected' : '' }}>No</option>
                                    <option value="1" {{ $product->featured == 1 ? 'selected' : '' }}>Yes</option>
                                </select>
                            </div>
                        </fieldset>
                        @error('featured') <span class="alert alert-danger text-center">{{$message}}</span> @enderror
                        <fieldset class="quantity">
                            <div class="body-title mb-10">Quantity <span class="tf-color-1">*</span></div>
                            <input class="mb-10" type="number" placeholder="Enter quantity" name="quantity" tabindex="0" value="{{ old('quantity', $product->quantity) }}" aria-required="true" min="0">
                        </fieldset>
                        @error('quantity') <span class="alert alert-danger text-center">{{$message}}</span> @enderror
                    </div>
                </div>
                <div class="flex items-center justify-between gap20">
                    <a href="{{ route('admin.products') }}" class="tf-button style-2">Cancel</a>
                    <button class="tf-button style-1" type="submit">Update Product</button>
                </div>
            </form>
            <!-- /form-edit-product -->
        </div>
        <!-- /main-content-wrap -->
    </div>
    <!-- /main-content-wrap -->
@endsection

@push('scripts')
<script>
    $(function(){
        
        $("#myFile").on("change", function(e){
            const photoInp = $("#myFile");
            const [file] = this.files;
            if(file){
                // Validate file type
                if (!file.type.startsWith('image/')) {
                    alert('Please select an image file');
                    return;
                }
                
                // Validate file size (max 5MB)
                if (file.size > 5 * 1024 * 1024) {
                    alert('File size should be less than 5MB');
                    return;
                }
                
                // Show preview
                $("#imgpreview img").attr('src', URL.createObjectURL(file));
                $("#imgpreview").show();
                $("#upload-file").hide();
            } else {
                $("#imgpreview").hide();
                $("#upload-file").show();
            }
        });

        $("#gFile").on("change", function(e){
            const gphotos = this.files;
            if (gphotos.length > 0) {
                // Clear previous previews
                $(".gitems").remove();
                
                $.each(gphotos, function(key, val){
                    // Validate file type
                    if (!val.type.startsWith('image/')) {
                        alert('Please select only image files');
                        return;
                    }
                    
                    // Validate file size (max 5MB)
                    if (val.size > 5 * 1024 * 1024) {
                        alert('File size should be less than 5MB');
                        return;
                    }
                    
                    // Add preview with remove button
                    const previewHtml = `
                        <div class="item gitems">
                            <img src="${URL.createObjectURL(val)}" />
                            <button type="button" class="remove-gallery-item" onclick="removeGalleryItem(this)" style="position: absolute; top: -5px; right: -5px; background: red; color: white; border: none; border-radius: 50%; width: 20px; height: 20px; cursor: pointer;">×</button>
                        </div>
                    `;
                    $("#galUpload").prepend(previewHtml);
                });
            }
        });

        // Clear main image preview when file input is cleared
        $("#myFile").on("input", function() {
            if (this.files.length === 0) {
                $("#imgpreview").hide();
                $("#upload-file").show();
            }
        });
        
        $("input[name='name']").on("input", function(){
            $("input[name='slug']").val(StringToSlug($(this).val()));
        });
    });

    function removeGalleryItem(button) {
        $(button).closest('.gitems').remove();
    }

    function removeMainImage() {
        $("#imgpreview").hide();
        $("#upload-file").show();
        $("#myFile").val(''); // Clear the file input
        $("#remove_main_image").val('1'); // Mark main image for removal
    }

    function StringToSlug(text) {
        return text
        .toString()                
        .trim()                    
        .toLowerCase()             
        .replace(/\s+/g, '-')      
        .replace(/[^\w\-]+/g, '')  
        .replace(/\-\-+/g, '-')    
        .replace(/^-+/, '')        
        .replace(/-+$/, '');      
    }
</script>
@endpush
