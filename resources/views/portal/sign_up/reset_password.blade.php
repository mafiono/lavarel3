@extends('layouts.portal', ['mini' => true])

@section('content')

    <div class="col-xs-12 home-back">
        <div class="main-contend main-opacity standalone">
            <div class="mini-main white-back">
                @include('portal.partials.pop_header_signup')
                
                <div class="form-registo">
                	<div class="col-xs-3 lin-xs-12 fleft">
                    	<div class="lin-xs-12 banner-back box-form-registo">
                            <img src="/assets/portal/img/banners/banner_ronaldo.png" alt="Banner Registo">
                        </div>
                    </div>
                	<div class="col-xs-9 lin-xs-9 fleft">
                        {!! Form::open(array('route' => array('recuperar_password'),'id' => 'saveForm', 'class' => 'lin-xs-12')) !!}
                            <div class="col-xs-78 lin-xs-12 fleft">
                                <div class="box-form-registo border-form-registo lin-xs-12">
                                    <div class="title-form-registo brand-title brand-color aleft">
                                        Esqueceu os seus dados?
                                    </div>
                                    
                                    <div class="col-xs-11 brand-descricao descricao-mbottom">
                                    	Se não se recorda dos seus dados de acesso, por favor preencha uma das referências de conta que apresentamos de seguida. Enviamos-lhe de seguida os seus dados de acesso para a sua conta de email.
                                    </div>

                                    <div class="registo-form">
                                        <label>Username</label>
                                        <input type="text" name="username" id="username" class="required" />
                                        <span class="has-error error" style="display:none;"> </span>
                                    </div>
                                       
                                    <div class="registo-form">
                                        <label>Email</label>
                                        <input type="email" name="email" id="email" class="required"/>
                                        <span class="has-error error" style="display:none;"> </span>
                                    </div>
                                    
                                    <div class="registo-form">
                                        <label>Data de Nascimento</label>
                                        <div class="fleft">
                                            <select name="age_day">
                                                <option value='1'>01</option>
                                                <option value='2'>02</option>
                                                <option value='3'>03</option>
                                                <option value='4'>04</option>
                                                <option value='5'>05</option>
                                                <option value='6'>06</option>
                                                <option value='7'>07</option>
                                                <option value='8'>08</option>
                                                <option value='9'>09</option>
                                                <option value='10'>10</option>
                                                <option value='11'>11</option>
                                                <option value='12'>12</option>
                                                <option value='13'>13</option>
                                                <option value='14'>14</option>
                                                <option value='15'>15</option>
                                                <option value='16'>16</option>
                                                <option value='17'>17</option>
                                                <option value='18'>18</option>
                                                <option value='19'>19</option>
                                                <option value='20'>20</option>
                                                <option value='21'>21</option>
                                                <option value='22'>22</option>
                                                <option value='23'>23</option>
                                                <option value='24'>24</option>
                                                <option value='25'>25</option>
                                                <option value='26'>26</option>
                                                <option value='27'>27</option>
                                                <option value='28'>28</option>
                                                <option value='29'>29</option>
                                                <option value='30'>30</option>
                                                <option value='31'>31</option>
                                            </select>
                                            <select name="age_month">
                                                <option value='1'>01</option>
                                                <option value='2'>02</option>
                                                <option value='3'>03</option>
                                                <option value='4'>04</option>
                                                <option value='5'>05</option>
                                                <option value='6'>06</option>
                                                <option value='7'>07</option>
                                                <option value='8'>08</option>
                                                <option value='9'>09</option>
                                                <option value='10'>10</option>
                                                <option value='11'>11</option>
                                                <option value='12'>12</option>
                                            </select>
                                            
                                            <select name="age_year">
                                                <option value='1905'>1905</option>
                                                <option value='1906'>1906</option>
                                                <option value='1907'>1907</option>
                                                <option value='1908'>1908</option>
                                                <option value='1909'>1909</option>
                                                <option value='1910'>1910</option>
                                                <option value='1911'>1911</option>
                                                <option value='1912'>1912</option>
                                                <option value='1913'>1913</option>
                                                <option value='1914'>1914</option>
                                                <option value='1915'>1915</option>
                                                <option value='1916'>1916</option>
                                                <option value='1917'>1917</option>
                                                <option value='1918'>1918</option>
                                                <option value='1919'>1919</option>
                                                <option value='1920'>1920</option>
                                                <option value='1921'>1921</option>
                                                <option value='1922'>1922</option>
                                                <option value='1923'>1923</option>
                                                <option value='1924'>1924</option>
                                                <option value='1925'>1925</option>
                                                <option value='1926'>1926</option>
                                                <option value='1927'>1927</option>
                                                <option value='1928'>1928</option>
                                                <option value='1929'>1929</option>
                                                <option value='1930'>1930</option>
                                                <option value='1931'>1931</option>
                                                <option value='1932'>1932</option>
                                                <option value='1933'>1933</option>
                                                <option value='1934'>1934</option>
                                                <option value='1935'>1935</option>
                                                <option value='1936'>1936</option>
                                                <option value='1937'>1937</option>
                                                <option value='1938'>1938</option>
                                                <option value='1939'>1939</option>
                                                <option value='1940'>1940</option>
                                                <option value='1941'>1941</option>
                                                <option value='1942'>1942</option>
                                                <option value='1943'>1943</option>
                                                <option value='1944'>1944</option>
                                                <option value='1945'>1945</option>
                                                <option value='1946'>1946</option>
                                                <option value='1947'>1947</option>
                                                <option value='1948'>1948</option>
                                                <option value='1949'>1949</option>
                                                <option value='1950'>1950</option>
                                                <option value='1951'>1951</option>
                                                <option value='1952'>1952</option>
                                                <option value='1953'>1953</option>
                                                <option value='1954'>1954</option>
                                                <option value='1955'>1955</option>
                                                <option value='1956'>1956</option>
                                                <option value='1957'>1957</option>
                                                <option value='1958'>1958</option>
                                                <option value='1959'>1959</option>
                                                <option value='1960'>1960</option>
                                                <option value='1961'>1961</option>
                                                <option value='1962'>1962</option>
                                                <option value='1963'>1963</option>
                                                <option value='1964'>1964</option>
                                                <option value='1965'>1965</option>
                                                <option value='1966'>1966</option>
                                                <option value='1967'>1967</option>
                                                <option value='1968'>1968</option>
                                                <option value='1969'>1969</option>
                                                <option value='1970'>1970</option>
                                                <option value='1971'>1971</option>
                                                <option value='1972'>1972</option>
                                                <option value='1973'>1973</option>
                                                <option value='1974'>1974</option>
                                                <option value='1975'>1975</option>
                                                <option value='1976'>1976</option>
                                                <option value='1977'>1977</option>
                                                <option value='1978'>1978</option>
                                                <option value='1979'>1979</option>
                                                <option value='1980'>1980</option>
                                                <option value='1981'>1981</option>
                                                <option value='1982'>1982</option>
                                                <option value='1983'>1983</option>
                                                <option value='1984'>1984</option>
                                                <option value='1985'>1985</option>
                                                <option value='1986'>1986</option>
                                                <option value='1987'>1987</option>
                                                <option value='1988'>1988</option>
                                                <option value='1989'>1989</option>
                                                <option value='1990'>1990</option>
                                                <option value='1991'>1991</option>
                                                <option value='1992'>1992</option>
                                                <option value='1993'>1993</option>
                                                <option value='1994'>1994</option>
                                                <option value='1995'>1995</option>
                                                <option value='1996'>1996</option>
                                                <option value='1997'>1997</option>
                                                <option value='1998'>1998</option>
                                                <option value='1999'>1999</option>
                                                <option value='2000'>2000</option>
                                                <option value='2001'>2001</option>
                                                <option value='2002'>2002</option>
                                                <option value='2003'>2003</option>
                                                <option value='2004'>2004</option>
                                                <option value='2005'>2005</option>
                                                <option value='2006'>2006</option>
                                                <option value='2007'>2007</option>
                                                <option value='2008'>2008</option>
                                                <option value='2009'>2009</option>
                                                <option value='2010'>2010</option>
                                                <option value='2011'>2011</option>
                                                <option value='2012'>2012</option>
                                                <option value='2013'>2013</option>
                                                <option value='2014'>2014</option>
                                                <option value='2015' selected="selected">2015</option>
                                            </select>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                    
                                    <div class="registo-form">
                                        <label>Código PIN</label>
                                        <input type="text" name="security_pin" id="security_pin" class="required" />
                                        <span class="has-error error" style="display:none;"> </span>
                                    </div>
                                    
                                    <div class="form-rodape aleft">
                                        <div class="col-xs-10 form-submit aleft" style="margin-left:10px;">
                                            <input type="submit" class="col-xs-10 brand-botao brand-link formSubmit" value="Continuar" />
                                        </div>
                                    </div>
    
                                </div>
                            </div>                                                        
                            
                            <div class="col-xs-45 lin-xs-9 fleft">
                                <div class="box-form-registo lin-xs-9">
                                    <div class="title-form-registo brand-title brand-color aleft">
                                        Contactos
                                    </div>
                                    
                                    <div class="registo-form">
                                    	<a target="_blank" href="#">
                                            <div class="brand-botao-rev brand-trans">
                                                CHAT
                                            </div>
                                        </a>
                                    </div>

                                    <div class="registo-form">
                                    	<a target="_blank" href="#">
                                            <div class="brand-botao-rev brand-trans">
                                                MENSAGEM
                                            </div>
                                        </a>
                                    </div>

                                    <div class="registo-form">
                                    	<a target="_blank" href="#">
                                            <div class="brand-botao-rev brand-trans">
                                                EMAIL
                                            </div>
                                        </a>
                                    </div>
                                    
                                    <div class="registo-form">
                                    	<a target="_blank" href="#">
                                            <div class="brand-botao-rev brand-trans">
                                                SKYPE
                                            </div>
                                        </a>
                                    </div>
                                    
                                    <div class="registo-form">
                                    	<a target="_blank" href="#">
                                            <div class="brand-botao-rev brand-trans">
                                                TELEFONE
                                            </div>
                                        </a>
                                    </div>
                                    
                                </div>                                
                            </div>
                            <div class="clear"></div>
                            
                        {!! Form::close() !!}
                    </div>
                    <div class="clear"></div>
                </div>
            </div>
        </div>
    </div>

@stop

@section('scripts')
    {!! HTML::script(URL::asset('/assets/portal/js/registo/recuperar_password.js')) !!}
@stop